# Caching

> Stores frequently-read data in fast memory to reduce database load and latency. Critical for any system at scale.

---

## 1. Redis — Popularity: 10/10

**Purpose:**
In-memory key-value cache. Stores strings, hashes, lists, sets, sorted sets, streams. Also handles queues, pub/sub, rate limiting, distributed locks. Used by virtually every modern web app.

**Alternatives:** (see below)

**When to learn it:** beginner — you already use it.

---

## 2. Memcached — Popularity: 7/10

**Purpose:**
Pure in-memory key-value cache. Simpler than Redis (no data structures, no persistence, no pub/sub). Multi-threaded, slightly faster for plain caching workloads. Older but still common.

**Alternatives:**
- See Redis (top of file).

**When to learn it:** intermediate — only if you encounter it; Redis usually wins.

---

## 3. Varnish — Popularity: 6/10

**Purpose:**
HTTP reverse-proxy cache. Caches **whole HTTP responses** in front of your app. Configured with VCL (Varnish Configuration Language). Used heavily by news sites, e-commerce.

**Alternatives:**
- **Nginx as cache** — 8/10 — Simpler, less powerful.
- **Cloudflare / Fastly** — 9/10 — Edge caching at the CDN layer (covered in `18-cdn-edge.md`).

**When to learn it:** intermediate — for high-traffic sites.

---

## 4. KeyDB — Popularity: 4/10

**Purpose:**
Multi-threaded fork of Redis. API-compatible. Better single-machine performance for write-heavy workloads.

**Alternatives:**
- **Dragonfly** — 5/10 — Modern Redis-compatible alternative; faster on multi-core.

**When to learn it:** advanced — niche; only if Redis becomes a bottleneck.

---

## 5. Hazelcast — Popularity: 4/10

**Purpose:**
Distributed in-memory data grid for Java/JVM ecosystems. Caches, distributed locks, computation. Common in enterprise Java.

**Alternatives:**
- **Apache Ignite** — 3/10 — Similar JVM-centric grid.
- **Infinispan** — 3/10 — Red Hat's grid.

**When to learn it:** advanced — only in JVM enterprise contexts.

---

## 6. Application-level / Framework caches — Popularity: 8/10

**Purpose:**
Built-in cache layers in frameworks. In Laravel: `Cache::remember()` with various drivers (file, Redis, DB, Memcached, array). Avoids re-computing query results, API responses, view fragments.

**When to learn it:** beginner — master Laravel's `Cache` and `Cache::tags()` first.

---

## Caching Strategies (concept worth knowing)

- **Cache-aside (lazy loading)** — App checks cache; if miss, reads DB and writes cache. Most common.
- **Read-through** — Cache library transparently reads from DB on miss.
- **Write-through** — Writes go to cache and DB simultaneously.
- **Write-behind** — Writes hit cache; flushed to DB asynchronously.
- **TTL (time-to-live)** — Auto-expire entries after N seconds.
- **Cache invalidation** — "There are only two hard things in CS: cache invalidation and naming things." Plan it carefully.
