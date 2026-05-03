# 05 — Databases

## One DB per service (the rule)

Each service owns its own schema. No cross-service joins. If service A needs data from service B, it asks via API or subscribes to events.

## Database map

| Service | DB | Schema name |
|---|---|---|
| Core API | PostgreSQL | `core` |
| Payments | PostgreSQL | `payments` |
| Chat | PostgreSQL | `chat` |
| Notifications | PostgreSQL | `notifications` |
| Resume Parser | PostgreSQL (only for parsed-result history) | `parser` |

In **dev** (local), all schemas can live inside the same Postgres instance for simplicity. In **prod**, use either separate Postgres databases or separate clusters per service depending on isolation needs.

## Why one Postgres, multiple schemas (in dev)

- One container, one credentials set, fewer moving parts
- Migrations stay per-service (each service's Laravel/FastAPI app manages its own migrations)
- Easy to split into separate clusters later — you've already encoded the boundary

## Core schema (high level — entities only, no fields here)

- `users` — base account (email, password, role group)
- `super_admins` — extends users with admin-only attributes
- `companies` — registered companies
- `company_users` — N:M between users and companies, with role
- `roles` and `permissions` — for fine-grained access (Spatie schema)
- `applicants` — public users who apply to jobs
- `jobs` — postings owned by a company
- `job_skills` — N:M between jobs and skill tags
- `skills` — canonical skill list
- `applications` — applicant ↔ job, with resume reference, status, parsed-skill match score
- `favourites` — applicant ↔ job
- `job_views` and `job_clicks` — analytics counters (often offloaded to Redis, periodically flushed)

## Payments schema

- `subscription_plans`, `subscriptions`, `invoices`, `webhook_events`

## Chat schema

- `conversations`, `conversation_members`, `messages`, `attachments`, `read_receipts`

## Notifications schema

- `notifications` (in-app), `device_tokens` (FCM), `email_log`, `delivery_attempts`

## Read replicas

For the Core schema, **one writer, one or more readers**:
- All writes go to the primary
- Heavy reads (job listings, dashboards) go to a read replica
- Laravel supports read/write connection split out of the box
- Add 1 replica when API CPU spends >30% on reads

## Sharding plan (for later, not v1)

The first table to outgrow a single node will be `applications` or `job_views`. When that day comes:
- Shard by `company_id` (or `job_id`) — keeps a single job's data together
- Use Vitess or Citus (Postgres partitioning) — don't roll your own
- Until then, vertical scaling + read replicas is enough

## Data lifecycle

| Data | Lifecycle |
|---|---|
| `users`, `companies`, `jobs` | Forever |
| `applications` | Forever (recruiter compliance) |
| `job_views` / `job_clicks` | Aggregated daily, raw rows kept 90 days |
| `notifications` | 90 days |
| `messages` (chat) | Forever (legal); soft-delete on user request |
| `webhook_events` | 1 year |

Old rows get archived to cold storage (S3 Glacier) via a scheduled job — keeps the hot tables small.

## Backups

- Daily logical dumps (`pg_dump`) to S3
- Weekly base backup
- Point-in-time recovery (PITR) via WAL archiving in prod
- Test restore monthly — a backup you've never restored is not a backup

## Migrations across services

- Each service runs its own migrations on deploy (`php artisan migrate --force` for Laravel; alembic for the Python parser)
- A service that adds a new column must do it backwards-compatibly: ship the migration first (additive), then ship the code that uses it
- **Never** rename or drop columns in one deploy — split into two deploys

## Counters that don't fit transactional DB

- `job_views`, `job_clicks`, `application_count_today` → Redis `INCR`
- A scheduled job flushes Redis counters into Postgres every 1–5 minutes
- This avoids hammering Postgres with one-row updates millions of times
