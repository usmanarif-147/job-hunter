# Stage 3 — Thundering Herd & Atomic Locks

## Problem statement
After Stage 2 we found three real problems hiding in our code:

1. **Hot row contention** — every `/jobs/{id}` runs
   `UPDATE views = views + 1` on the *same row* when many users view
   the same job. MySQL serializes those writes with row-level locks,
   so throughput collapses on viral pages.

2. **Thundering herd (cache stampede)** — the moment `jobs.top` TTL
   expires, every concurrent request gets a cache miss and *all of
   them* run the heavy DB query at once.

3. **Cache thrashing** — calling `Cache::forget('jobs.top')` on every
   single view means the cache is killed and rebuilt over and over
   during high traffic, providing zero benefit.

We need a fix for #2 and #3 right now. #1 (hot row) needs a fast
in-memory counter — we'll fix it properly when Redis enters the picture.

## New concept — Atomic locks & request coalescing
An **atomic lock** is a "you can hold it or someone else can, but never
both" primitive. Only the *first* request to arrive after a cache miss
acquires the lock, runs the query, and writes the cache. Every other
request either waits a short moment for the lock holder to finish, or
keeps serving slightly stale data. This is called **request coalescing**:
N concurrent rebuild attempts are coalesced into 1.

Laravel ships this out of the box: `Cache::lock($key, $seconds)` returns
a lock you can `get()` or `block($wait, $callback)`. Combined with
`Cache::remember`, you build a stampede-proof cache.

To kill thrashing, we also stop calling `Cache::forget` on every view.
A short TTL (5–10s) is good enough — perfect freshness isn't worth the
storm we just saw.

## Requirements
- Update `top()` so the cache miss path is guarded by a lock. Pseudo-flow:
  *check cache → if miss, try to acquire `jobs.top.lock` → only the
  holder rebuilds; others either wait or return stale.*
- Remove `Cache::forget('jobs.top')` from `show()`. Lower the TTL of
  `jobs.top` (e.g. 10 seconds) instead.
- Verify in Debugbar: open 2 tabs, expire the cache, refresh both fast.
  You should see **exactly one** `job_listings` SELECT, not two.

## Vocabulary to remember
- Hot row / row-level lock contention
- Atomic lock / mutex
- Request coalescing
- Stale-while-revalidate
- Cache stampede / thundering herd / dog-pile

## What's next (preview, do not implement yet)
File-based locks live on disk — slow, and don't work across multiple
servers. The hot-row write storm on `/jobs/{id}` is also still there.
Stage 4 introduces the idea we've been circling around: we need a
**fast, shared, in-memory store** for both the cache and the counter.
That's the door we'll walk through to reach Redis.
