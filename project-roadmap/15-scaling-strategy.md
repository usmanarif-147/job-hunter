# 15 — Scaling Strategy

Designed from day 1 for production traffic: 2M registered users, 1,000 average concurrent, **1M peak concurrent**.

## The principle: scale what gets hit

Most parts of the system see <1% of total traffic. The hot paths are:

1. Public job listing (`GET /search/jobs`)
2. Single job page (`GET /jobs/{id}`)
3. Apply endpoint (`POST /jobs/{id}/apply`)
4. WebSocket connections (chat + realtime push)
5. Search

These get aggressive scaling. Everything else runs at a baseline.

## Layer-by-layer scaling plan

### Edge — Cloudflare
Already global, already auto-scales. Cache job listing pages aggressively at the CDN with short TTLs (60 seconds). At 1M concurrent readers the CDN absorbs ~95% of GET traffic; only the misses reach our gateway.

### Gateway — Kong
Run **3+ Kong instances** behind Cloudflare (HA + load balancing). Stateless. Add more as traffic grows.

### Microservices (10 of them)
All stateless. Scaled by adding more pods/containers via Kubernetes HorizontalPodAutoscaler (HPA).

| Service | Scale trigger | Min/Max pods (production target) |
|---|---|---|
| Identity | CPU + introspection QPS | 5 / 50 |
| Companies | CPU | 3 / 20 |
| Jobs | CPU + apply QPS | 10 / 100 |
| Payment | CPU | 3 / 10 |
| Notification | Kafka consumer lag | 5 / 50 |
| Chat | WebSocket connection count | 10 / 100 |
| Media | CPU + upload QPS | 5 / 30 |
| Resume Parser | Kafka consumer lag | 5 / 50 |
| Search | CPU | 3 / 20 |
| Reporting | scheduled — fixed pool | 2 / 5 |

### Database — Postgres (per service)
The hardest tier to scale. Per-service strategy:

1. **Vertical scaling first** — bigger instance (more RAM = better cache hits)
2. **Read replicas** — split reads to followers; writes to primary
3. **PgBouncer in transaction-pool mode** — handles 50+ pods without exhausting Postgres connections
4. **Sharding** — last resort. By `company_id` for Jobs Service. Use Vitess or Citus.

Cache-first: most reads should never reach Postgres.

### Cache / Queue — Redis
- Shared Redis cluster for cache + sessions, namespaced by service
- Separate Redis instance for Reverb pub/sub (heavy fan-out)
- Separate Redis for queue work (Notification, Reporting use Horizon)
- Redis cluster mode when memory grows

### Event bus — Kafka
- Production cluster: 3 brokers minimum, multi-AZ
- Replication factor 3, min in-sync replicas 2
- Topic partitions sized for max consumer parallelism (start at 12 per major topic)
- Use managed Kafka (AWS MSK, Confluent Cloud, Aiven) — running it yourself is operationally heavy

### WebSocket — Chat Service (Reverb)
Each Reverb pod holds ~10K connections. For 1M concurrent → 100+ pods. Sticky sessions are required (Cloudflare / ALB supports this). Redis pub/sub fans out across pods.

If Reverb hits a limit, the migration target is **Centrifugo** or **Node.js + Socket.IO** — same protocol abstraction so clients don't change.

### Search — Meilisearch
Single instance handles 2M docs comfortably. Read replicas for HA. If query QPS exceeds one node's capacity, run a cluster.

## Per-tier scaling effort

| Tier | Type | Scaling |
|---|---|---|
| Cloudflare | Stateless | Free / automatic |
| Kong | Stateless | Add instances |
| Microservices | Stateless | HPA in K8s |
| Chat (Reverb) | Stateful (sticky connections) | Add pods + sticky sessions |
| Redis | Stateful | Cluster mode + sharding |
| Postgres (per service) | Stateful | Read replicas → sharding |
| Kafka | Stateful | Add brokers + partitions |
| Meilisearch | Stateful | Replicas for read; single primary |

## Hot job problem

A viral job: "FAANG hiring 100 engineers!" → millions of clicks within hours.

Mitigations:

- **CDN cache** for the public job page (60s TTL): 1M readers, ~50K cache misses
- **Stale-while-revalidate**: serve a slightly old page while we recompute the fresh one
- **Per-key cache lock** at Redis: only one Postgres query per minute per hot job, even on cache miss
- **Pre-warm**: when a recruiter creates a job, pre-populate cache and CDN

## The apply spike

100 slots, hundreds applying simultaneously. Covered in `07-job-application-flow.md`. The DB row lock + Redis counter + unique constraint ensure correctness.

## Off-peak scaling

Scale down to minimum pods overnight in the dominant timezone. Saves significantly on compute costs.

## Pre-launch load testing

Run load tests with **k6** before any major launch:

1. 10,000 simultaneous job-listing requests
2. 1,000 simultaneous applications to one slot-capped job (verify cap holds)
3. 100,000 simultaneous WebSocket connections
4. A bulk-email broadcast of 5,000 recipients
5. Sustained 5K req/sec across the gateway for 1 hour

Find breakpoints. Fix or scale up. Re-test.

## Observability is the prerequisite

You cannot scale what you cannot measure. File 16 (Observability) is in place from day 1.
