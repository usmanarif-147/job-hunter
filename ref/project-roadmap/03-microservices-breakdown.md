# 03 — Microservices Breakdown

## The split — what becomes a service, what stays inside Laravel

| # | Service | Language / Tech | Why it's separate |
|---|---|---|---|
| 1 | **Core API** | Laravel 11+ | Owns users, companies, jobs, applications. The brain. |
| 2 | **Chat Service** | Node.js + Socket.IO (or Laravel Reverb) | WebSocket workloads need long-lived connections — different process model than PHP-FPM |
| 3 | **Notification Service** | Laravel queue workers | High-throughput fan-out; isolating it means email/FCM lag doesn't slow API |
| 4 | **Resume Parser** | Python + FastAPI | Python ecosystem dominates PDF/DOCX parsing & ML libraries |
| 5 | **Payment Service** | Laravel + Cashier | Stripe webhooks have strict ordering & retry rules; isolate to keep clean |
| 6 | **Search Service** | Meilisearch (used directly via SDK) | Specialised search engine; no wrapping needed |
| 7 | **Reporting Service** | Laravel scheduler + queue | Generates PDFs/CSVs; runs on its own worker pool to not steal capacity |

You can run all of these on one machine for local dev. In production they scale independently.

## Why not split the Core API further (yet)

You could split Core API into Auth / Jobs / Applications / Companies. Don't, until you have to. Reasons:

- One developer or small team ships features faster in a modular monolith
- Cross-service transactions (a user applies → company notified → applicant counter incremented) are easier inside one DB
- You can refactor later when boundaries are clearer

When to actually split? When **two or more teams** can't merge code without conflicts, or when one part of the API has wildly different scale/latency needs.

## The ownership rule

Each service **owns its own data**. No service reads another service's database directly. They communicate via:

- **HTTP API** — for "I need an answer right now"
- **Event bus (Kafka)** — for "something happened, anyone who cares can react"

Example flow when an applicant applies to a job:

```
1. User → Core API: POST /jobs/{id}/apply  (HTTP)
2. Core API: validate, lock slot, save application (own DB)
3. Core API → Kafka: emits "ApplicationCreated" event
4. Notification Service: reads event, fans out FCM + email
5. Resume Parser: reads event, queues parse job
6. Search Service: reads event, updates application count for job
```

Core API doesn't *call* the other services. It emits an event. Decoupled.

## Why each tech choice

### Core API → Laravel
You already know it. Massive ecosystem. Eloquent, Sanctum, Cashier, Horizon, Pest — all batteries included. No reason to switch the part of the stack you're best at.

### Chat → Node.js + Socket.IO **or** Laravel Reverb
PHP-FPM is request/response. WebSockets are long-lived. Two reasonable options:
- **Laravel Reverb** — stay in PHP; perfect if you want one ecosystem.
- **Node.js + Socket.IO** — proven at scale; richer realtime ecosystem; learn Node along the way.

Pick Reverb for v1 (less to learn). Migrate to Node only if Reverb becomes a bottleneck.

### Notifications → Laravel queue workers
Push and email are fire-and-forget. Laravel + Horizon + Redis is the most boring, most reliable setup possible. Don't reinvent.

### Resume Parser → Python + FastAPI
PHP is bad at parsing PDFs / DOCX / extracting skills. Python has `pdfplumber`, `docx2txt`, `spacy` (NLP), `scikit-learn`. FastAPI is fast and async. This service is a no-brainer to be Python.

### Payments → Laravel
Cashier handles 90% of Stripe complexity. Webhooks need careful idempotency, but Laravel handles it well.

### Search → Meilisearch
Faster to set up than Elasticsearch, free, single binary, fuzzy search out of the box. Plenty for ~2M jobs.

### Reporting → Laravel scheduler
Generating PDFs and CSVs is CPU-heavy and slow. Run it on a separate worker pool so it doesn't steal capacity from API calls.

## Microservice candidates we deliberately reject (for now)

- **A separate Auth service** — overkill until SSO / multi-tenant federation appears
- **A separate "Company" service** — too coupled to Jobs and Users; split causes pain, not benefit
- **A frontend BFF (backend-for-frontend) per panel** — adds latency without value at this scale

## Where Rust would fit (your interest)

The single best place to introduce Rust here is the **Resume Parser** — but Python wins for ML/NLP libraries. The next-best place is a future **search-ranking service** or a **rate-limiter sidecar**. For v1, don't add Rust just for resume value; pick it later when there's a real fit (e.g., a high-throughput counter service).
