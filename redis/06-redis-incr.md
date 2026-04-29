# Stage 6 — Atomic Counters with Redis INCR

## Problem statement
Stage 5 made the cache shared and fast — but **`/jobs/{id}` is still
broken at scale**. Every visit runs:

```
UPDATE job_listings SET views = views + 1 WHERE id = ?
```

When 500 users hit the same job, MySQL serialises them through a
**row-level lock**. Count stays correct, but throughput collapses,
connections pile up, and the page hangs. We named this **hot-row
contention** earlier. A cache cannot fix it — caches help reads, not
writes. We need a different storage primitive for the counter itself.

## New concept — Atomic counters in Redis (`INCR`)
Redis has a command called `INCR key`. It increments the integer value
of a key by 1, **atomically, in RAM, without locks**. Hundreds of
thousands per second, microsecond latency, no contention.

Instead of UPDATE-ing the DB on every page view, we:

1. Call `Redis::incr("job:views:{$id}")` on each view.
2. Periodically (every minute via the scheduler) **flush** the
   accumulated Redis counts into the DB and reset them.
3. When displaying a view count, show `DB.views + Redis.pending` so
   users always see the up-to-date total.

This pattern has a name: **write-behind / write-back caching**. The DB
is still the source of truth; Redis is a fast buffer that batches
writes. It introduces **eventual consistency** — the DB lags Redis by
up to one flush interval, but the combined view is always exact.

## Requirements
- Replace `$job->increment('views')` in `JobListingController@show`
  with `Redis::incr("job:views:{$id}")`.
- When displaying view counts (in both `top()` and `show()`), surface
  `DB.views + (int) Redis::get("job:views:{$id}")`. A small accessor on
  the model (e.g. `getDisplayViewsAttribute`) keeps the views clean.
- Create an Artisan command `php artisan jobs:flush-views` that:
  - Uses `SCAN` (not `KEYS`) to find all `job:views:*` keys.
  - For each key, adds the value to the matching DB row inside a
    transaction, then `DEL`s the Redis key.
- Schedule the command to run every minute (`bootstrap/app.php`
  `withSchedule(...)` block, or `app/Console/Kernel.php`).

## How to verify
- `for i in $(seq 1 100); do curl -s http://localhost:8000/jobs/5 > /dev/null; done`
- `docker compose exec redis redis-cli -n 0 GET 'laravel-database-job:views:5'`
  → returns `100`. DB row unchanged.
- Run `php artisan jobs:flush-views`. Redis key gone, DB views += 100.
- Compare response time vs the old UPDATE-based approach — much faster
  and far steadier under concurrency.

## Vocabulary to remember
- **Atomic counter** (vs read-modify-write race)
- **Write-behind / write-back caching**
- **Eventual consistency** (vs strong consistency)
- **Hierarchical keys** (`job:views:42` convention in Redis)
- **`SCAN` vs `KEYS`** (production-safe key iteration)

## What's next (preview, do not implement yet)
The leaderboard query is still SQL: `ORDER BY views DESC LIMIT 5`.
Stage 7 replaces it with a Redis **sorted set** so `/jobs/top` becomes
a single `ZREVRANGE` call — zero DB queries on the read path, even
for the leaderboard.
