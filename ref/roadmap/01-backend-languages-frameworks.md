# Backend Languages & Frameworks

> The runtime + framework that runs your business logic. In a microservice world, different services often use different languages.

---

## 1. PHP / Laravel — Popularity: 9/10

**Purpose:**
PHP is a server-side scripting language; Laravel is its dominant modern framework. Used heavily for monolithic web apps, REST APIs, admin panels, and SaaS backends. Excellent ecosystem (Eloquent ORM, queues, broadcasting, Horizon, Reverb).

**Alternatives:**
- **Symfony** — 7/10 — Enterprise PHP framework. More verbose than Laravel but powers many large apps; Laravel itself uses many Symfony components.
- **CodeIgniter** — 4/10 — Lightweight legacy PHP framework, common in older projects in South Asia.
- **CakePHP / Yii** — 3/10 — Older PHP frameworks, fading.

**When to learn it:** beginner — you already know this.

---

## 2. Node.js (Express / NestJS) — Popularity: 10/10

**Purpose:**
JavaScript on the server. Express is minimal and ubiquitous; NestJS adds structure (modules, decorators, DI) similar to Laravel/Spring. Strong choice for realtime APIs, BFFs (backend-for-frontend), and lightweight microservices.

**Alternatives:**
- **Fastify** — 7/10 — Faster Express alternative; growing fast.
- **Hono** — 6/10 — Modern, edge-friendly (Cloudflare Workers, Deno, Bun).
- **Koa** — 4/10 — Express's "successor" by the same team; smaller community.
- **AdonisJS** — 4/10 — Laravel-like framework for Node; nice but niche.

**When to learn it:** intermediate — high ROI for microservices.

---

## 3. Python (Django / FastAPI) — Popularity: 9/10

**Purpose:**
Python dominates ML/AI, data, and scripting; on the web, Django is "Python's Laravel" (batteries-included), FastAPI is a modern async framework great for APIs. FastAPI is rising fast.

**Alternatives:**
- **FastAPI** — 9/10 — Type-hinted, async, auto-docs. Top choice for new Python APIs.
- **Flask** — 7/10 — Minimal, classic.
- **Django REST Framework** — 8/10 — Django + REST APIs; very common.
- **Litestar** — 4/10 — Younger FastAPI alternative.

**When to learn it:** intermediate — Job 1 in your list explicitly requires Python.

---

## 4. Go (Golang) — Popularity: 9/10

**Purpose:**
Compiled, fast, statically-typed, built for concurrency. Standard for cloud-native infrastructure (Docker, Kubernetes, Terraform are all written in Go). Excellent for microservices, CLIs, and high-throughput APIs.

**Alternatives:**
- **Gin** (framework) — 9/10 — Most popular Go web framework.
- **Echo** — 7/10 — Similar to Gin, slightly more features.
- **Fiber** — 7/10 — Express-like API for Go developers from JS.
- **Chi** — 6/10 — Minimal, idiomatic.

**When to learn it:** intermediate-to-advanced — high-paying jobs love Go.

---

## 5. Rust — Popularity: 7/10

**Purpose:**
Systems language with C-level performance and memory safety. Used for performance-critical microservices, infrastructure tooling, and embedded. Steeper learning curve than Go. Companies like Discord, Cloudflare, and many fintechs use Rust for hot-path services.

**Alternatives:**
- **Axum** (framework) — 9/10 — Modern, async, the default Rust web framework today.
- **Actix Web** — 7/10 — Very fast, mature.
- **Rocket** — 5/10 — Easy to learn, slower release cycle.
- **Warp** — 4/10 — Composable, niche.

**When to learn it:** advanced — great for microservices but more time investment than Go. You've already touched it; deepen here for specialization.

---

## 6. Java / Kotlin (Spring Boot) — Popularity: 9/10

**Purpose:**
Java + Spring Boot is the enterprise default — banks, insurance, telecom, big corporates. Kotlin is Java's modern cousin (also runs on JVM), now official for Android.

**Alternatives:**
- **Spring Boot** — 10/10 — The framework. Everywhere in enterprise.
- **Micronaut** — 5/10 — Lighter, faster startup; rising.
- **Quarkus** — 5/10 — GraalVM-native, cloud-friendly.
- **Ktor** (Kotlin) — 4/10 — Lightweight Kotlin framework.

**When to learn it:** advanced — only if targeting enterprise/finance roles.

---

## 7. C# / .NET — Popularity: 8/10

**Purpose:**
Microsoft's stack. Massive in enterprise, gaming (Unity), and Windows ecosystems. ASP.NET Core is fast and modern; cross-platform now.

**Alternatives:**
- **ASP.NET Core** — 10/10 — The framework.
- **Minimal APIs** — 7/10 — Lighter style for microservices.

**When to learn it:** advanced — only for .NET-heavy markets.

---

## 8. Ruby on Rails — Popularity: 6/10

**Purpose:**
The framework that inspired Laravel. Still strong at startups (GitHub, Shopify, Basecamp). Beautiful DX, less hyped now.

**Alternatives:**
- **Sinatra** — 3/10 — Minimal, like Flask for Ruby.
- **Hanami** — 2/10 — Modern alternative; very niche.

**When to learn it:** advanced — only if targeting Rails shops.

---

## 9. Elixir (Phoenix) — Popularity: 5/10

**Purpose:**
Functional language on the Erlang VM. Insanely good at concurrency and realtime (millions of WebSocket connections). Phoenix LiveView pioneered server-rendered reactive UIs.

**Alternatives:**
- Phoenix is the only mainstream framework.

**When to learn it:** advanced — niche but loved; great for realtime use cases.
