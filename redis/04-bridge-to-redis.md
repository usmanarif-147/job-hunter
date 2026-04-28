# Stage 4 — The Bridge to Redis (concept-only)

## Problem statement
After Stages 0-3 you've solved the read-side beautifully: cache + lock +
double-check make `/jobs/top` fast and stampede-proof. But three real
problems remain that file cache and file locks cannot solve:

1. **Hot-row contention** — `/jobs/{id}` still does `UPDATE views =
   views + 1` on the same DB row. At 500 clicks/sec on row 42, MySQL
   serialises every write through that row's lock. The count is correct,
   but the page is slow and your DB pool fills up. Cache cannot help —
   caches solve reads, not writes.

2. **Per-server cache** — your file cache lives on **one container's
   filesystem**. The day you scale to 2 app containers behind a load
   balancer, each one has its own private cache. User A's cache write
   on container 1 is invisible to User B's request on container 2.
   Cache hit rate collapses; DB load multiplies.

3. **Disk-bound lock speed** — file locks are disk operations. SSDs do
   roughly 100,000 ops/sec. RAM does 10,000,000 ops/sec — about 100x
   faster. At very high concurrency, the lock layer itself becomes the
   bottleneck, even if the protected work is fast.

## New concept — A fast, shared, in-memory store
We need ONE store that sits **outside** any app container, lives in RAM
(microsecond speed), is shared by every container, and offers a few extra
primitives: atomic counters (so we can stop UPDATE-ing rows), built-in
TTL, atomic locks, and pub/sub for live updates.

That store is **Redis**.
- It runs as its own service (a separate container in docker-compose).
- All app containers connect to the same Redis instance, so cache state
  is shared.
- It supports `INCR` — increment a counter atomically without any DB row
  involved. This single feature makes hot-row contention disappear.
- Lock checks are now RAM ops — microseconds instead of milliseconds.

## Requirements (this stage = reading only)
- No code changes here. Just understand the three problems and why the
  next stage installs Redis.
- Be ready to discuss in Stage 5: why we add `redis` as a docker service,
  why we flip `CACHE_STORE=redis`, and why `/jobs/top` will work
  unchanged but become measurably faster.

## What's next — Stage 5
Add Redis to docker-compose, switch the cache driver, **change no
controller code**, and watch Debugbar report dramatically faster
response times. After that, Stages 6-10 will use Redis-specific
features (`INCR` for the view counter, sorted sets for the leaderboard,
sessions/queue/rate-limit/pub-sub) one by one — that's where Redis goes
from "faster cache" to "tool that solved problems file cache never could."
