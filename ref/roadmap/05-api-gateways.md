# API Gateways

> A single entry point that sits in front of many backend services. Handles routing, authentication, rate limiting, request transformation, logging, and SSL termination. Crucial in microservice architectures so clients don't talk to many services directly.

---

## 1. Kong — Popularity: 9/10

**Purpose:**
Open-source API gateway built on Nginx + OpenResty (Lua). Plugin ecosystem for auth, rate limiting, transformations, observability. Self-hosted or managed (Kong Konnect). Very common in microservice setups.

**Alternatives:** (see below)

**When to learn it:** intermediate — you've already touched it; deepen via the admin API and plugins.

---

## 2. AWS API Gateway — Popularity: 9/10

**Purpose:**
AWS-managed gateway. Tight integration with Lambda, IAM, Cognito, CloudWatch. Two flavors: **REST API** (older, fully featured) and **HTTP API** (newer, cheaper, faster).

**When to learn it:** intermediate — required for AWS serverless work.

---

## 3. Traefik (gateway mode) — Popularity: 8/10

**Purpose:**
Modern reverse proxy + gateway. Auto-discovers services from Docker, Kubernetes, Consul. Excellent default for container-based stacks. Built-in Let's Encrypt for SSL.

**When to learn it:** intermediate — easy on-ramp into the gateway world.

---

## 4. Nginx (as gateway) — Popularity: 8/10

**Purpose:**
Nginx can serve as an API gateway with manual config (proxy_pass, rate limiting, auth subrequests). No fancy admin UI but proven, lightweight, and free. Many teams start here before adopting Kong.

**When to learn it:** beginner — you already use Nginx; learn its gateway features next.

---

## 5. Tyk — Popularity: 6/10

**Purpose:**
Open-source API gateway in Go. Cleaner config than Kong, dashboard included. Strong analytics and developer portal. Smaller community than Kong.

**When to learn it:** intermediate — alternative to Kong worth knowing.

---

## 6. KrakenD — Popularity: 5/10

**Purpose:**
High-performance gateway focused on **API composition** (aggregating multiple backend calls into one response). Stateless, declarative config in JSON.

**When to learn it:** advanced — niche but powerful for BFF patterns.

---

## 7. Apigee (Google) — Popularity: 7/10

**Purpose:**
Enterprise API management platform from Google. Heavy-duty: full lifecycle, monetization, developer portal, analytics. Expensive.

**Alternatives:**
- **Mulesoft Anypoint** — 6/10 — Salesforce's enterprise API platform.
- **Azure API Management** — 7/10 — Microsoft's equivalent.
- **AWS API Gateway** — 9/10 — Cheaper, AWS-native.

**When to learn it:** advanced — only in large corporates.

---

## 8. Envoy (as gateway) — Popularity: 8/10

**Purpose:**
High-performance L7 proxy (used inside Istio). Can run as a standalone API gateway (Envoy Gateway project). Powerful but more complex config than Kong/Traefik.

**When to learn it:** advanced — typically learn it via service mesh first.

---

## What an API Gateway Actually Does

- **Routing** — `/api/users` → user-service, `/api/orders` → order-service
- **Authentication** — verify JWTs, API keys, OAuth tokens before forwarding
- **Rate limiting** — block abusive clients
- **Request/Response transformation** — rewrite headers, paths, bodies
- **SSL termination** — handle HTTPS at the gateway
- **Caching** — cache responses
- **Observability** — log, trace, metric every request
- **CORS** — handle cross-origin headers
- **Circuit breaking** — stop hammering a failing backend
- **Versioning** — route `/v1/...` and `/v2/...` to different services
