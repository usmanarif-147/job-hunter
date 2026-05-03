# 15 — Scaling Strategy

Scale targets: 2M users registered, 1,000 average concurrent, **1M peak concurrent** (during a viral spike or a massive recruiter campaign).

## The principle: scale what gets hit, ignore the rest

Most parts of the system see <1% of total traffic. Optimizing them is wasted effort. The hot paths are:

1. Public job listing (`GET /jobs`)
2. Single job page (`GET /jobs/{id}`)
3. Apply endpoint (`POST /jobs/{id}/apply`)
4. WebSocket connections (chat + notifications)
5. Search

That's it. Scale these aggressively, leave everything else alone.

## Layer-by-layer scaling plan

### Edge — Cloudflare
Already global, already auto-scales. Cache job listing pages aggressively at the CDN with short TTLs (60 seconds). 1M concurrent readers → CDN absorbs ~95% of GET traffic; only 50K hit our servers.

### Gateway — Kong
Run **2+ Kong instances** behind Cloudflare. Stateless. Add more as traffic grows.

### Core API (Laravel)
Stateless. Scaled by adding more pods/containers. Sweet spot per pod: ~100–200 concurrent requests on a 2-vCPU container. For peak: 50–100 pods.

K8s **HorizontalPodAutoscaler** scales pods based on CPU or queue depth. Set targets like:

- Min 5 pods (always running)
- Max 100 pods (handles peak)
- Scale up at 70% CPU, scale down at 40%

### Database — Postgres
The hardest tier to scale. Strategy:

1. **Vertical scaling first** — bigger instance (more RAM = more cache hits). Easy and effective up to a point.
2. **Read replicas** — split reads to followers; writes still go to primary. Laravel supports this natively. Adds replica lag (typically <100ms — fine for us).
3. **Connection pooling** — Postgres handles ~200 connections cleanly. With 100 API pods × 10 connections each = 1,000. We need **PgBouncer** in front of Postgres in transaction-pool mode.
4. **Sharding** — last resort. By `company_id` for `jobs` and `applications` tables. Vitess or Citus.

**Cache-first**: most reads should never reach Postgres. If they do, Postgres is doing too much.

### Cache / Queue — Redis
- One Redis cluster for sessions + cache (cluster mode if memory grows beyond ~10GB)
- Separate Redis instance for queues (Horizon recommends this)
- Replicas for read-only operations

### WebSocket — Reverb
Each Reverb pod holds ~10K connections. For 1M concurrent → 100+ pods. Or migrate to **Centrifugo / Node + Socket.IO** which can hold 100K+ per pod.

This is the biggest scaling jump in the system. Plan for it: keep the chat protocol abstracted so we can switch backend without changing clients.

### Search — Meilisearch
Single instance handles 2M docs comfortably. Replicas for HA. If search QPS exceeds one node's capacity, run a cluster.

## Stateless services scale linearly. Stateful tiers are the hard part.

| Tier | Type | Scaling effort |
|---|---|---|
| Cloudflare | Stateless | Free / automatic |
| Kong | Stateless | Add instances |
| Laravel API | Stateless | HPA in K8s |
| Reverb | Stateful (sticky connections) | Add pods + sticky sessions |
| Redis | Stateful | Cluster mode + sharding |
| Postgres | Stateful | Read replicas → sharding |
| Meilisearch | Stateful | Replicas for read; single primary |

## Hot job problem

A viral job post: "FAANG hiring 100 engineers!" → millions of clicks within hours.

Mitigations:

- **CDN**: cache the public job page for 60 seconds → 1M readers, ~50K cache misses
- **Stale-while-revalidate**: serve a slightly old page while we recompute the fresh one
- **Per-key cache lock** at Redis: only one Postgres query per minute even on cache miss
- **Pre-warm**: when a recruiter creates a job, pre-populate cache and CDN

## The apply spike

100 slots, hundreds applying simultaneously. Already covered in `07-job-application-flow.md`. The DB row lock is the answer for v1.

## Cost-conscious autoscaling

- **Off-peak**: scale to minimum (5 API pods, 1 of everything else) overnight in the main timezone
- **Peak hours**: scale up
- **Special events** (campaign launch): scale up manually beforehand, don't wait for autoscaler latency

## Pre-launch load testing

Before going to production at scale, run load tests with **k6**:

1. Simulate 10,000 simultaneous job-listing requests
2. Simulate 1,000 simultaneous applications to the same job (with cap of 100 — verify cap holds)
3. Simulate 50,000 simultaneous WebSocket connections
4. Simulate a bulk-email broadcast of 5,000 recipients

Find the breakpoints. Fix or scale up. Re-test.

## Observability is the prerequisite for scaling

You cannot scale what you cannot measure. File 16 (Observability) must be in place before any of this scaling matters.

## What scales naturally vs needs care

| Naturally | Needs care |
|---|---|
| GET requests behind CDN | WebSocket fan-out |
| Stateless app pods | Postgres write throughput |
| Queue workers (just add more) | Connection pool sizing |
| Kong | Redis cluster topology |
| Object storage (S3) | Ordered delivery (Kafka partitions) |

Spend most of your scaling brain on the right column.
