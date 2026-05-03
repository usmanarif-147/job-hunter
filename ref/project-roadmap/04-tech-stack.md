# 04 — Tech Stack

The single source of truth for **what we use** at each layer. Each pick has a one-line reason.

## Frontend

| Layer | Choice | Why |
|---|---|---|
| Public site (`job-hunter.com`) | Nuxt 3 (Vue) | SEO matters for job listings; SSR is mandatory |
| Company panel (`company.job-hunter.com`) | Vue 3 SPA | Authenticated, SEO doesn't matter, SPA is enough |
| Admin panel (`admin.job-hunter.com`) | Vue 3 SPA | Same reasoning as company panel |

Why Vue and not React? You'll be picking up frontend on the side; Vue is friendlier and your job market lists Vue/Nuxt explicitly. Pick one and stay consistent across all three apps.

## Backend

| Component | Choice | Why |
|---|---|---|
| Core API | Laravel 11+ | Your strongest skill; covers 80% of needs out of the box |
| Chat | Laravel Reverb | Stay in PHP; first-party; enough for our scale |
| Notifications | Laravel + Horizon | Mature, observable queue worker pool |
| Resume Parser | Python + FastAPI | Best ecosystem for PDFs and NLP |
| Payments | Laravel + Cashier (Stripe) | One-line subscription support |
| Reporting | Laravel scheduler | Reuse the same codebase, isolated worker pool |

## Data

| Component | Choice | Why |
|---|---|---|
| Primary DB | PostgreSQL 16 | Better JSON, full-text search, partitioning, advisory locks |
| Cache + Sessions + Rate limit | Redis 7 | One Redis cluster covers all three |
| Queue | Redis (via Laravel) | Simplest for v1; revisit when traffic justifies Kafka |
| Event bus | Kafka | When microservices grow; for v1, skip and use Redis Streams |
| Object storage | S3 (prod) / MinIO (dev) | Resumes, chat files, generated reports |
| Search | Meilisearch | Fast, free, fuzzy search out of the box |

**Note:** Postgres over MySQL because it handles concurrent writes (advisory locks, `SELECT ... FOR UPDATE SKIP LOCKED`) more gracefully — critical for the slot-capacity problem (see file 07).

## Infrastructure

| Component | Choice | Why |
|---|---|---|
| Container runtime | Docker | Industry standard |
| Local orchestration | Docker Compose | Trivial multi-service local dev |
| Production orchestration | Kubernetes (managed: EKS or DOKS) | Auto-scaling, rolling deploys, self-healing |
| API gateway | Kong | You've used it; rich plugin ecosystem |
| Reverse proxy | Nginx (in front of Laravel) | Battle-tested |
| CDN + DNS + SSL | Cloudflare | Free tier covers everything |
| Object storage in prod | AWS S3 | The standard |
| Cloud provider | AWS | Largest ecosystem; Job 1 in your list requires it |

## Observability

| Concern | Choice | Why |
|---|---|---|
| Errors | Sentry | Free tier; best-in-class for exceptions |
| Logs | Grafana Loki | Cheap, integrates with Grafana |
| Metrics | Prometheus + Grafana | Standard pair |
| Traces | OpenTelemetry → Tempo | Vendor-neutral instrumentation |
| Uptime | UptimeRobot | Free; pings public endpoints |

## CI / CD & Team Workflow

| Concern | Choice | Why |
|---|---|---|
| Version control | GitHub | Cheapest path; Actions included |
| CI/CD | GitHub Actions | No extra service to operate |
| Code review | GitHub PRs | Built-in |
| Issue tracking | GitHub Issues + Projects | One platform; less context-switching |
| Image registry | GitHub Container Registry (GHCR) | Free for the org; same auth as code |
| Deployment | ArgoCD (when on K8s) | GitOps: cluster mirrors what's in Git |

## Auth & Security

| Concern | Choice | Why |
|---|---|---|
| Auth (web) | Laravel Sanctum (cookie session) | Simplest secure default for SPAs on same root domain |
| Auth (mobile) | Sanctum API tokens | Same package, different driver |
| Roles & permissions | spatie/laravel-permission | Standard in Laravel ecosystem |
| Secrets | AWS Secrets Manager (prod), `.env` (local) | Cloud-managed in prod; keep `.env` out of git |
| WAF / DDoS | Cloudflare | Free tier handles both |

## External services we will integrate

- **Stripe** — subscriptions, webhooks
- **Firebase Cloud Messaging (FCM)** — mobile push
- **Postmark or AWS SES** — transactional + bulk email (Postmark for great deliverability; SES for cheap volume)
- **ClamAV** — virus-scanning uploaded files (runs as a sidecar)

## Things we deliberately don't pick (yet)

- **Service mesh (Istio/Linkerd)** — overkill until many microservices and many teams
- **Elasticsearch** — Meilisearch is enough; revisit if we need analytics
- **Kafka** — start with Redis Streams; move to Kafka when 5+ services need shared event log
- **Vault** — AWS Secrets Manager is simpler in v1
- **Datadog** — Loki + Prometheus + Sentry covers it for free

This file is the canonical reference. If you ever wonder "what did we pick for X?" — look here.
