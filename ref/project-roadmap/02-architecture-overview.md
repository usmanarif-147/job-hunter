# 02 — Architecture Overview

## The big picture (left → right)

```
   Browsers / Mobile apps
   (3 frontend apps)
            │
            ▼
   Cloudflare (CDN + DDoS + DNS + SSL)
            │
            ▼
   Kong API Gateway (auth, routing, rate limit)
            │
   ┌────────┼─────────┬──────────┬───────────┬──────────┐
   ▼        ▼         ▼          ▼           ▼          ▼
 Core API  Chat    Notification  Resume   Payment    Search
 (Laravel) (Node)  Service       Parser   (Laravel)  (Meilisearch)
                  (Laravel)     (Python)
   │        │         │          │           │          │
   └────┬───┴────┬────┴────┬─────┴────┬──────┴────┬─────┘
        ▼        ▼         ▼          ▼           ▼
     MySQL    Redis     Kafka /     S3 /       Stripe
     Postgres (cache,   RabbitMQ    MinIO      (external)
              queue,    (events)    (files)
              pubsub)
```

## The layers, explained

### 1. Edge — Cloudflare
Sits in front of everything. Free tier covers DNS, SSL (HTTPS), DDoS protection, basic WAF, and CDN for static assets. Cheap insurance.

### 2. Gateway — Kong
Single entry point. Receives every request. Decides: which service should handle this? Is this user authenticated? Are they over their rate limit? Adds a request-ID for tracing.

### 3. Backend services (multiple)
Each one owns a slice of the system. They don't share databases — they talk via the queue/event bus or by calling each other's APIs through the gateway.

| Service | Language | Job |
|---|---|---|
| **Core API** | Laravel (PHP) | Auth, users, companies, jobs, applications |
| **Chat** | Node.js + Socket.IO (or Laravel Reverb) | WebSocket connections, live messages |
| **Notifications** | Laravel queue workers | FCM pushes + emails (fan-out, retries) |
| **Resume Parser** | Python + FastAPI | Extract text & skills from PDFs/DOCX |
| **Payments** | Laravel + Cashier | Stripe subscriptions, webhooks |
| **Search** | Meilisearch (used directly) | Full-text job search |

### 4. Data layer
- **MySQL / Postgres** — transactional source of truth (users, jobs, applications)
- **Redis** — cache, queue, session, rate limit counter, pub/sub
- **Kafka or RabbitMQ** — async event bus between services
- **S3 / MinIO** — resumes, chat attachments, generated reports
- **Stripe** — outsourced billing

## Style: modular monolith first, microservices where forced

A pure-microservices design is overkill for a 1-developer start. The pragmatic plan:

- **Phase 1 (MVP):** One Laravel app for everything except realtime chat, resume parsing, and search (those are too different to live in PHP comfortably). Three "external" services: Chat, Resume Parser, Search.
- **Phase 2 (scale):** Pull out Notifications and Payments as separate services when load justifies it.
- **Phase 3 (microservices):** Split the Laravel monolith into Auth, Jobs, Applications, Companies — only when team size / load demands it.

See `03-microservices-breakdown.md` for the full split.

## Communication between services

- **Synchronous** (request needs an answer now) → HTTP through Kong
- **Asynchronous** (fire-and-forget, can take seconds) → Kafka or Redis Streams
- **Realtime to clients** → WebSocket via Chat service
- **Push to mobile** → FCM via Notification service

## What lives where (rule of thumb)

- **State that must be ACID** → SQL DB owned by one service
- **Throwaway hot data** (sessions, cache, counters) → Redis
- **Cross-service events** ("user-applied-to-job") → Kafka
- **Big binary blobs** (resumes, chat files, reports) → S3

This separation is the single most important system-design decision. Every later chapter assumes it.
