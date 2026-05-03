# 18 — Local Development Setup

The goal: a fresh laptop should run the entire stack with **one command**: `docker compose up -d`.

## What runs locally

| Component | Local image | Port |
|---|---|---|
| Core API (Laravel) | Built from `Dockerfile` | 9000 (PHP-FPM internal) |
| Chat Service (Reverb) | Same Laravel image, different command | 8080 |
| Notification Service | Same Laravel image, queue worker | — |
| Resume Parser (Python) | `python:3.12-slim` + dependencies | 8000 |
| Payment Service | Laravel image | 9001 (PHP-FPM internal) |
| Postgres | `postgres:16-alpine` | 5432 |
| Redis | `redis:7-alpine` | 6379 |
| Meilisearch | `getmeili/meilisearch:v1` | 7700 |
| MinIO (S3 substitute) | `minio/minio:latest` | 9002 (API), 9003 (console) |
| ClamAV | `clamav/clamav:latest` | 3310 |
| Mailhog (fake SMTP) | `mailhog/mailhog` | 1025 (SMTP), 8025 (UI) |
| Nginx | `nginx:1.25` | 80 → routes to PHP-FPM and Reverb |
| Kong | `kong:3` | 8001 (admin), 8000 (proxy) |
| Redis Commander | `rediscommander/redis-commander` | 8081 |
| phpMyAdmin / pgAdmin | `dpage/pgadmin4` | 8082 |

The frontend apps (Nuxt, Vue) run on the host with `npm run dev` rather than in Docker — faster HMR, simpler.

## Three frontend apps

| App | Port | URL (with `/etc/hosts` entry) |
|---|---|---|
| Public | 3000 | `http://job-hunter.local` |
| Company | 3001 | `http://company.job-hunter.local` |
| Admin | 3002 | `http://admin.job-hunter.local` |

Add to `/etc/hosts`:

```
127.0.0.1 job-hunter.local company.job-hunter.local admin.job-hunter.local api.job-hunter.local
```

## Why MinIO instead of real S3 locally

- Free, runs offline, S3 API-compatible
- Same SDK (`league/flysystem-aws-s3-v3` in PHP, `boto3` in Python)
- Switch to real S3 in production by changing env vars only

## Why Mailhog instead of real SMTP

- Captures all outgoing emails into a local UI
- Never accidentally email a real user from dev
- Inspect email rendering, links, and headers
- One env var change to enable

## Why ClamAV locally too

So we test the virus-scanning path. Same code, same flow. ClamAV's container is heavy (~1GB image, downloads signatures on first run) — disable it for routine dev, enable when working on file upload features.

## Environment configuration

Each service has a `.env` (NOT committed). The `.env.example` (committed) lists all keys with safe defaults for local dev. The `entrypoint.sh` (already in your project) auto-creates `.env` from the example on first start.

## Hot reload

- **Laravel:** code is bind-mounted (`./app:/var/www/html`); changes are live
- **Python parser:** uvicorn `--reload` flag picks up file changes
- **Frontend (Nuxt/Vue):** Vite HMR

So dev-loop is: save file → see change in <1 second.

## Database state

- Postgres and MinIO use **named volumes** (`mysql_data`, `minio_data`) — survive container restarts
- Migrations run on startup (your `entrypoint.sh` already does this)
- Seeders create realistic test data: 50 companies, 200 jobs, 1000 applicants, sample plans
- A `make reset` command (or `php artisan migrate:fresh --seed`) wipes and reseeds

## Useful Make commands

`Makefile` at the repo root makes common operations memorable:

```
make up           # docker compose up -d
make down         # docker compose down
make logs         # tail all services
make test         # run all tests
make migrate      # php artisan migrate
make seed         # php artisan db:seed
make reset        # fresh migrate + seed
make shell        # bash into core-api container
```

## Local Kong setup

Kong runs in **DB-less declarative mode** for local dev — config is a YAML file (`kong.yml`) committed to the repo. No admin DB needed; faster startup; matches v1 prod for simplicity.

The YAML maps:

- Services → upstream URLs (the Docker service names: `core-api:9000`, `reverb:8080`, etc.)
- Routes → host + path patterns
- Plugins → CORS, rate limit, request-id

Changes to `kong.yml` require a Kong reload (`make kong-reload`).

## What we deliberately skip locally

- **Kafka** — Redis Streams works for v1 events; Kafka adds operational weight without much value for solo dev
- **Prometheus + Grafana** — log to stdout and read with `docker compose logs`; install Grafana later when investigating production behavior
- **Cloudflare** — there's no edge to simulate locally; just serve over HTTP locally
- **K8s** — `docker compose` is enough; bring K8s in when prepping production deploys

## Performance on a laptop

The full stack uses ~3GB RAM with everything running. For 8GB laptops, disable services you're not actively touching (`docker compose stop clamav meilisearch`).

For very heavy local development (running tests + parser + chat all at once), consider:

- Use **Hetzner cheap VPS** (~$5/month) with VS Code Remote SSH — more RAM, real Linux
- Use **GitHub Codespaces** — pay only when developing

## Common local-dev pitfalls

| Symptom | Likely cause |
|---|---|
| `php artisan` fails with DB error | Postgres not yet healthy — wait or check `entrypoint.sh` retry loop |
| File uploads work but downloads 403 | MinIO bucket policy not set — check seeder |
| Redis says OOM | Forgot to flush dev data; `docker compose exec redis redis-cli flushall` |
| Frontend can't reach API | Forgot to add `/etc/hosts` entries; or Kong route missing |
| Webhooks from Stripe don't arrive | Use **Stripe CLI** (`stripe listen --forward-to ...`) to forward to localhost |

## Stripe local testing

Stripe webhooks can't reach `localhost`. Use the Stripe CLI:

```
stripe listen --forward-to localhost:8000/v1/billing/webhook
```

It opens a tunnel and prints a webhook signing secret to put in `.env`. Now Stripe events fire locally.
