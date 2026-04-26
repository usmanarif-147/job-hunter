# Stage 2 — Cache Invalidation

## Problem statement
After Stage 1, `/jobs/top` is served from cache for 60 seconds.
But during that 60s, users keep viewing jobs at `/jobs/{id}`,
and `views` counts are changing in the DB.
Result: `/jobs/top` shows **stale data** — the cached top-5 doesn't reflect
what's actually happening in the database. The cache and the source of
truth are out of sync.

This is the famous problem of **cache invalidation**:
*how do you know when to throw away cached data?*

## New concept — Cache invalidation strategies
Two common ways to keep cache in sync with the DB:

1. **Forget on write (lazy)** — when data changes, delete the cached value.
   Next reader gets a cache miss → rebuilds from DB → re-caches.
   Simple. Recompute happens only if someone actually asks.

2. **Write-through (eager)** — when data changes, immediately recompute and
   overwrite the cache. Cache is always warm, no miss ever.
   More work, but zero stale window.

Interview rule of thumb:
- Reads ≪ writes → use **forget** (lazy rebuild). Avoid wasted recomputes.
- Reads ≫ writes → use **write-through**. Readers never wait.

For our app: `/jobs/{id}` (writes views) is hit way more than `/jobs/top` (reads).
So **forget on write** is the right choice — `Cache::forget('jobs.top')` inside `show()`.

## Requirements
- Inside `JobListingController@show`, after `$job->increment('views')`,
  call `Cache::forget('jobs.top')`.
- Verify:
  - Hit `/jobs/top` → cached.
  - Hit `/jobs/5` once → cache key is gone.
  - Hit `/jobs/top` again → fresh query, fresh order. No 60s wait.

## Vocabulary to remember (for interviews)
- **Cache hit / miss**
- **TTL (time-to-live)**
- **Stale cache**
- **Cache invalidation**
- **Cache busting** (= forget on write)
- **Write-through cache** (= recompute on write)
- **Source of truth** (the DB — cache is just a copy)

## What's next (preview, do not implement yet)
You just made `show()` do two things on every visit:
1. Increment `views` in DB.
2. Bust the cache.

Now imagine 500 users hit `/jobs/5` in the same second.
- The DB gets 500 `UPDATE` statements on the **same row** → row-level lock contention.
- The cache gets deleted 500 times → fine, but wasteful.
- Worse: `/jobs/top` rebuilds during that storm — and if 50 users hit `/jobs/top`
  in that same second on a cold cache, **all 50** run the same heavy query
  (this is called the **thundering herd / cache stampede** problem).

That pain leads us to Stage 3 — **race conditions, locking, and the thundering herd**.
