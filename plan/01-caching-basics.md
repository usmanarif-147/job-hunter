# Stage 1 — Caching Basics

## Problem statement
Every time someone opens `/jobs/top`, Laravel runs the same
`SELECT ... ORDER BY views DESC LIMIT 5` query against MySQL.
If 100 users visit per minute, MySQL answers the same question 100 times.
The data barely changes between requests, so most of that work is wasted.
On a real site (thousands of visits) this becomes a major bottleneck — DB CPU
goes up, response time goes up, and your DB becomes the slowest part of the app.

## New concept — Caching
A cache is a temporary store that holds the *result* of an expensive operation
so the next caller can reuse it instead of recomputing.

Key ideas:
- **Cache hit** → answer found in cache → fast (no DB query).
- **Cache miss** → not in cache → run the query, store the result, return it.
- **TTL (time-to-live)** → how long the cached value stays valid before expiring.
- **Key** → a unique string that identifies the cached value (e.g. `jobs.top`).

Laravel ships with `Cache::remember($key, $ttl, fn() => ...)` which does the
hit/miss/store dance for you in one call.

For Stage 1 we will use the **file cache driver** (default in `.env` if you
haven't changed it). It stores cached values as files on disk under
`storage/framework/cache/`. Slow compared to memory, but zero setup —
perfect for learning the *concept* before we worry about *speed*.

## Requirements
- Change `CACHE_STORE=file` in `.env` (currently `database`).
- Update `JobListingController@top` to wrap the query in `Cache::remember`
  with a key like `jobs.top` and a TTL of e.g. 60 seconds.
- Hit `/jobs/top` once → see DB query in logs.
- Hit `/jobs/top` again immediately → no DB query (served from cache).
- After 60 seconds → cache expires → next hit re-queries DB.

## How to verify
- Enable query logging or use Laravel Debugbar / `php artisan pail`.
- First request: 1 SELECT in the log. Next 10 requests within 60s: 0 SELECTs.

## What's next (preview, do not implement yet)
You will quickly notice a new problem: when someone views a job and its
`views` count goes up, `/jobs/top` still shows **stale** data for up to 60s.
That pain is the door to Stage 2 — **cache invalidation**.
