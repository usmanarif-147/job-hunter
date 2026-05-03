# 19 — Production Deployment

What changes from local Docker Compose → real production.

## The big shifts

| Local | Production |
|---|---|
| Docker Compose (one host) | Kubernetes (managed, multi-node) |
| Bind-mounted code | Code baked into immutable Docker images |
| MinIO | AWS S3 (or Cloudflare R2) |
| Mailhog | Postmark + AWS SES |
| Self-hosted Postgres | AWS RDS for Postgres (managed) |
| Self-hosted Redis | AWS ElastiCache or DigitalOcean Managed Redis |
| Self-signed / no SSL | Let's Encrypt via Cloudflare or cert-manager |
| Stripe CLI tunnel | Real Stripe webhook URL |
| `.env` file | AWS Secrets Manager |
| `docker compose logs` | Loki + Grafana |
| Manual `docker compose up` | ArgoCD GitOps deploy |

## Hosting options (pick one)

### Option A — AWS (recommended for resume value)

| Layer | AWS service |
|---|---|
| Compute (containers) | EKS (managed Kubernetes) |
| Database | RDS for Postgres |
| Cache | ElastiCache for Redis |
| Object storage | S3 |
| Load balancer | ALB |
| DNS | Route 53 (or Cloudflare DNS) |
| Email | SES |
| Secrets | Secrets Manager |
| Logs / Metrics | CloudWatch (or self-hosted Loki/Prometheus) |

Free tier helps for the first 12 months: EC2 t3.micro free, RDS db.t3.micro free, S3 5GB free.

### Option B — DigitalOcean (cheaper, simpler)

- DOKS (managed K8s) — $12/month for the control plane
- Managed Postgres — from $15/month
- Managed Redis — from $15/month
- Spaces — from $5/month for S3-compatible storage
- Predictable monthly bills, simpler UI than AWS

### Option C — Hetzner + Coolify / Dokku (cheapest)

- One Hetzner VPS (~$5/month, lots of RAM)
- Coolify or Dokku as a self-hosted Heroku alternative
- No K8s — just Docker Compose at production scale
- Best for early-stage / pre-revenue: very low cost
- Limit: doesn't auto-scale to 1M concurrent. Migrate to AWS/DOKS when needed.

For your roadmap and learning goals: **start with Hetzner / Coolify in MVP**, **migrate to AWS EKS** once you want to add the AWS resume line.

## DNS and SSL

- **DNS at Cloudflare** (free) — proxies traffic, hides origin IP, free SSL at the edge
- All three subdomains (`job-hunter.com`, `company.*`, `admin.*`, `api.*`) point to the load balancer
- Cloudflare's "Full (strict)" SSL mode → encrypted browser ↔ Cloudflare ↔ origin

## Image pipeline

Every merge to `main` produces a Docker image:

```
GitHub Actions:
  1. Run tests
  2. Build image
  3. Tag with commit SHA + semantic version
  4. Push to GitHub Container Registry (GHCR)
  5. Update K8s manifest in `infra` repo (via PR auto-merge)

ArgoCD:
  1. Notices the manifest change
  2. Syncs the cluster — rolls out new pods
  3. Old pods stay alive until new ones pass health checks (rolling update)
```

Zero-downtime deploys are free with this pattern.

## Database migrations on deploy

Critical pattern: **migrations are part of the deploy, but only one pod runs them**.

Two options:

1. **K8s `Job`** runs migrations once before the rollout starts; new pods only start after the Job succeeds
2. **Helm chart pre-install hook** runs migrations as part of the chart upgrade

Whatever you do, **don't run `php artisan migrate` from every pod** — they'd race. The current local `entrypoint.sh` does this for dev only.

Backwards-compatibility rule: every migration must be deployable while old pods are still running.

## Rolling update strategy

K8s deployment manifest sets:

- `maxSurge: 25%` — at most 25% extra pods during rollout
- `maxUnavailable: 0` — never reduce capacity

Result: pods are added new → old ones drained → users never see downtime.

## Health checks

Every service exposes:

- `livenessProbe` — `/health/live` (kill the pod if this fails)
- `readinessProbe` — `/health/ready` (only route traffic when this passes)
- `startupProbe` — for slow-starting apps (Laravel startup with cache warming)

## Secrets management

In production:

1. Secrets stored in **AWS Secrets Manager** (or **External Secrets Operator** in K8s pulling from Vault/AWS)
2. K8s injects them into pod environment variables at runtime
3. Code reads them as normal env vars (`env('STRIPE_SECRET')`)
4. **Never** in Git, **never** in container images, **never** in logs

## Backups

- **Postgres:** RDS automated backups (daily) + 7-day retention; weekly off-site dump to S3 Glacier
- **Object storage:** S3 versioning + cross-region replication for resumes
- **Redis:** stateful data is in Postgres; Redis is a cache only — don't back it up

Test restoring backups quarterly. A backup that's never been restored is not a backup.

## Logging in production

- Stdout from each pod → Promtail / Vector → Loki
- Loki retention: 30 days (cheap)
- Grafana dashboards saved as YAML in `infra` repo (versioned)

## Metrics in production

- Each service exposes `/metrics` (Prometheus format)
- Prometheus scrapes every 15 seconds
- Grafana dashboards for: API latency, queue depth, DB QPS, WebSocket connections, Stripe webhook health

## Alerts in production

PagerDuty (or Opsgenie, or Slack-only) wired to Prometheus Alertmanager. Initial alerts:

- Service down >2 min
- p99 latency >2s for 5 min
- Error rate >5%
- DB primary unreachable
- Disk >85%

## Security hardening in prod (top items)

- Cloudflare WAF (free tier blocks basic attacks)
- Force HTTPS everywhere (HSTS preload list)
- IAM least-privilege (each service has its own IAM role)
- Rate-limit the gateway aggressively
- Enable AWS GuardDuty (or equivalent) for anomaly detection
- Run CIS-benchmark scan on K8s cluster
- Container images scanned in CI (Trivy)
- Dependencies scanned in CI (composer audit, npm audit, pip-audit)

## Cost estimate (rough)

| Stage | Monthly cost |
|---|---|
| MVP on Hetzner + Coolify | $10–30 |
| Early growth on AWS small | $200–500 |
| 2M users, 1M concurrent peak | $5,000–15,000 (depending on traffic mix) |

Most of the cost at scale is: bandwidth (CDN), compute (EKS pods at peak), and database. Cache aggressively → bandwidth and compute drop.

## Disaster recovery

- RTO (recovery time objective): 1 hour
- RPO (recovery point objective): 1 hour of data loss tolerable

Achieved by:

- Automated daily DB backups + point-in-time recovery
- Multi-AZ Postgres (auto-failover on primary failure)
- S3 cross-region replication
- Infrastructure-as-Code (Terraform) so we can rebuild in another region in <1 hour

For a v1 startup, full multi-region active-active is overkill. Multi-AZ is the right tradeoff.
