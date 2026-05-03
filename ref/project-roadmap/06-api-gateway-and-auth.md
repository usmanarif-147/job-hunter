# 06 — API Gateway & Auth

## Why a gateway at all

Three frontends + multiple backend services = chaos without a front door. Kong gives us:

- **One URL** that the frontend talks to (`api.job-hunter.com`)
- **One place** to enforce auth, rate limits, CORS, request IDs
- **Routing** by hostname / path to the right backend service
- **Plugin ecosystem** for logging, analytics, transformations

## The flow

```
Browser → Cloudflare → Kong → Backend service
                        │
                        ├── Strip / verify auth header
                        ├── Inject request-ID for tracing
                        ├── Rate-limit by IP / user
                        ├── Route based on host + path
                        └── Log to centralized logger
```

## Routing table (illustrative)

| Incoming | Routed to |
|---|---|
| `api.job-hunter.com/v1/auth/*` | Core API |
| `api.job-hunter.com/v1/jobs/*` | Core API |
| `api.job-hunter.com/v1/applications/*` | Core API |
| `api.job-hunter.com/v1/billing/*` | Payment Service |
| `api.job-hunter.com/v1/chat/*` | Chat Service (HTTP REST) |
| `wss.job-hunter.com` | Chat Service (WebSocket) |
| `api.job-hunter.com/v1/parse/*` | Resume Parser |
| `api.job-hunter.com/v1/notifications/*` | Notification Service |

WebSockets get their own subdomain to keep gateway routing simple.

## Auth — the design

Three login realms, **one auth library** (Laravel Sanctum), **three guards**.

| Realm | Guard | Token home | Cookie scope |
|---|---|---|---|
| Super admin | `admin` | Sanctum cookie | `admin.job-hunter.com` |
| Company user | `company` | Sanctum cookie | `company.job-hunter.com` |
| Applicant | `web` | Sanctum cookie | `job-hunter.com` |

Each realm has its own login endpoint. Sanctum issues a session cookie scoped to the matching subdomain. Cookies cannot leak across realms.

For mobile clients (later), the same backend issues bearer tokens (also Sanctum, different driver) — same logic, no cookies.

## How the gateway knows you're authenticated

Two approaches; we use **session-cookie pass-through** for v1:

1. Frontend hits Kong with the `session_cookie`
2. Kong forwards the request, including the cookie, to the backend
3. The backend (Laravel) validates the cookie via Sanctum middleware
4. If invalid, the backend returns 401 — Kong propagates it

This keeps Kong stateless and gives us one source of truth (the Laravel session store in Redis).

When we add mobile or third-party API consumers, we move auth into Kong itself with the **JWT plugin** — Kong validates the JWT and forwards user context as headers (`X-User-ID`, `X-User-Roles`).

## Roles & permissions

Use `spatie/laravel-permission` inside the Core API:

- **Roles** are sets of permissions (e.g., `admin.support`, `company.recruiter`, `company.billing-admin`)
- **Permissions** are atomic actions (e.g., `jobs.create`, `applicants.view`)
- A user can have many roles
- Policies in Laravel check permissions on each protected action

Each realm gets its own role namespace so a `company.recruiter` cannot accidentally inherit `admin.support`.

## Multi-tenancy: companies have their own users

A company has many users (`company_users` join table). Each company-user has a role *within that company*. When they log in, the auth system resolves:

1. Which company are they currently acting on behalf of? (URL `/{company-uuid}`)
2. Does this user belong to that company?
3. What role do they have inside it?

This is checked once per request via middleware, then the resolved company is attached to the request for the rest of the request lifecycle.

## Rate limiting

- **Per IP** at Kong (e.g., 100 req/min per IP)
- **Per user** at Kong (e.g., 600 req/min per authenticated user)
- **Per endpoint** for sensitive routes (e.g., login: 5 attempts / 15 min)
- **Per company** for write-heavy endpoints (e.g., 10 bulk-emails per hour)

Rate limit counters live in Redis. Kong's plugin handles increment + check.

## CORS

- Allowed origins are the three frontends only
- Configured at Kong (one place); backends don't need their own CORS middleware
- Credentials allowed (so cookies work across the API gateway subdomain)

## Request tracing

Kong injects a unique `X-Request-ID` header on every request. All backend services log it. All emitted events carry it. When debugging, you can grep one ID across every service log to see the full journey.

## Failure modes

- If Kong is down → the whole API is down. Run **two Kong instances** behind a Cloudflare load balancer (or AWS ALB).
- If a backend service is down → Kong returns 502 for that route, others keep working.
- If the auth backend is down → the gateway can't validate sessions. Cache validated sessions in Redis (`auth.user:{id}`) with a short TTL so brief outages don't log everyone out.
