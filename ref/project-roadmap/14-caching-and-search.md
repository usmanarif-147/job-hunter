# 14 — Caching & Search

## Caching strategy — what to cache, where, and when to invalidate

Most performance problems are solved by caching the right thing. Most cache bugs are caused by caching the wrong thing.

### What goes in cache (Redis)

| Data | Key pattern | TTL | Invalidation |
|---|---|---|---|
| Job listing pages | `jobs:list:{filters_hash}` | 60 seconds | TTL only — listings are tolerant of slight staleness |
| Single job page | `job:{id}` | 5 minutes | Invalidate on `job.updated` event |
| Company public profile | `company:{uuid}:public` | 5 minutes | Invalidate on profile update |
| Skills list (autocomplete) | `skills:all` | 1 hour | Invalidate when admin adds a skill |
| User session | `session:{id}` | session lifetime | Cleared on logout |
| User permissions | `auth.user:{id}` | 5 minutes | Invalidate on role/permission change |
| Active subscription check | `sub:active:{company_id}` | 60 seconds | TTL only |
| Counters (views, clicks) | `job:{id}:views` | persistent until flushed | Flushed to DB every minute |
| Rate-limit counters | `rate:{ip}:{route}` | 1 minute | TTL |

### Cache pattern: cache-aside (lazy loading)

```
function getJob(id) {
    if (cache has job:{id}) return cache value
    job = read from Postgres
    set cache job:{id} = job (TTL 5 minutes)
    return job
}
```

Simple, correct, the default everywhere.

### Stampede protection

If 1,000 users request the same uncached page at once, all 1,000 hit Postgres. Solutions:

- **Per-key locks**: only one process recomputes; others wait briefly
- **Soft-TTL** (Laravel `Cache::flexible`): serve stale value while one process refreshes in background
- Use both for hot pages (homepage, popular job listing)

### What we explicitly do NOT cache

- Application slot counts (always read live from DB or Redis counter)
- Resume parse results in transit (we want fresh views)
- Anything in the admin panel (low traffic, freshness matters more)

### Cache layer order (when many caches stack up)

```
Browser (HTTP cache headers)
  → Cloudflare CDN (static assets, public images)
    → Nginx micro-cache (optional, 1-second cache on hot routes)
      → Redis (application cache)
        → Postgres (source of truth)
```

Each layer absorbs traffic so the lower layers see less. This is how systems serve millions of requests with a small DB.

---

## Search — Meilisearch

Why Meilisearch:

- Single binary, easy to run
- Fast, fuzzy, typo-tolerant out of the box
- Free, plenty for ~2M jobs
- Simple HTTP API

Elasticsearch is more powerful but operationally heavier. Postgres full-text could work but lacks features like instant typo tolerance, faceted search, and ranking customization at our growth rate.

### What we index

A flattened "JobDocument" per published job:

- `id`, `title`, `description` (truncated), `company_name`, `location`, `tags`, `skills[]`
- `posted_at`, `apply_before`, `salary_range`
- `is_open` (boolean — affects ranking, doesn't filter — closed jobs still appear if specifically searched)

We do **not** index resumes or applications.

### Keeping the index in sync

When a job changes in Postgres, Meilisearch must hear about it.

**Option A — synchronous (don't):** every write also writes to Meilisearch. Tightly coupled; if Meilisearch is down, job creation fails.

**Option B — event-driven (do):**
- Job changes emit a `JobChanged` event
- A consumer listens, fetches latest job state, upserts into Meilisearch
- If Meilisearch is down, the event sits in the queue and is processed when it recovers

Laravel Scout supports this pattern — uses queues by default.

### Reindex on schema change

A scheduled job can reindex the entire `jobs` table from scratch (e.g., after a search algorithm tweak). Reindexing 2M jobs takes minutes; do it during low-traffic windows.

### Search filters and facets

The job-board search must support filters:

- Skills (multi-select)
- Location
- Salary range
- Posted date range
- Company

Meilisearch supports filterable attributes natively. We mark these in the index settings on first launch.

### Ranking

Default Meilisearch ranking is good enough. Tweaks worth considering later:

- Boost `title` matches over `description` matches
- Boost recently posted jobs
- Penalize closed jobs (don't hide — let them appear, lower)

### Search service in front of Meilisearch?

Don't add one for v1. The frontend hits Meilisearch via a server-side proxy in the Core API (which adds auth + filters out drafts).

A standalone "Search Service" makes sense once we add personalization (recommend based on user history) — defer.

### Privacy

The Meilisearch instance is **not exposed to the public internet**. The frontend never talks to it directly. All search requests go through Core API, which:

- Authenticates the user (or treats as anonymous)
- Applies tenancy filters (e.g., admins see closed jobs; public doesn't)
- Forwards to Meilisearch with those filters baked in

This is how we keep the search engine fast (no auth check inside Meili) and safe (no leaks).
