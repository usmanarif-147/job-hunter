# Stage 5 — Redis Arrives

## Problem statement
In Stage 4 we *physically observed* the cache fragmentation problem:
two containers, two private file caches, two different views of the
"top jobs" data. This is exactly what happens in real production with
multiple servers — each with its own disk. File-based cache cannot
solve it. We need ONE store that lives outside any single container,
that all containers can read and write to over the network.

## New concept — Redis as a shared in-memory cache
Redis is a separate service: a small, blazingly fast key-value store
that lives in RAM. We will run it as its own Docker container alongside
`app`, `app2`, `mysql`, etc. Both Laravel app containers will connect
to it over the Docker network at `redis:6379`.

Two beautiful properties:

1. **Shared by all clients** — any container that connects sees the
   same keys and values. Cache fragmentation disappears.

2. **Drop-in replacement for the file driver** — Laravel's `Cache::*`
   API is an abstraction. We change one line in `.env`
   (`CACHE_STORE=file` → `CACHE_STORE=redis`) and zero controller code.
   `Cache::remember`, `Cache::lock`, `Cache::forget` all keep working.

To talk to Redis, PHP needs the `phpredis` extension (a C extension
compiled into PHP). We install it in the Dockerfile via `pecl`.

## Requirements
- Add a `redis` service to `docker-compose.yml` (image: `redis:7-alpine`).
- Remove the per-container cache bind mounts (`./docker/cache/app1`,
  `./docker/cache/app2`) — no longer needed; Redis is the shared cache.
- Add `app` and `app2` `depends_on: redis` so the order is correct.
- Update `Dockerfile`: `RUN pecl install redis && docker-php-ext-enable redis`.
- Update `.env`: `CACHE_STORE=redis` and `REDIS_HOST=redis`
  (the Docker service name, not 127.0.0.1).
- Rebuild and restart: `docker compose up -d --build`.
- Verify with `redis-cli ping`, `php -m | grep redis`, and a quick
  `Cache::put`/`Cache::get` smoke test inside tinker.
- Visit `/jobs/top` across both containers — values should match.

## Vocabulary to remember
- **In-memory store** / **key-value store**
- **PHP extension** vs **Composer package**
- **Cache driver** as an abstraction
- **Docker service DNS** (services reach each other by name)

## What's next (preview)
Same code is now Redis-backed. We've solved fragmentation. But we
still haven't fixed the **hot-row contention** on `/jobs/{id}` —
`UPDATE views = views + 1` is still hitting MySQL on every click.
Stage 6 will replace that with `Redis::INCR` — a true atomic, lock-free
counter that lives in RAM. That's where Redis goes from "faster cache"
to "tool that does things SQL fundamentally can't."
