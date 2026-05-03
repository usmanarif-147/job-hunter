# 21 — Rollout Phases

You don't build all of this at once. Ship in small slices, each shippable to real users (or yourself for testing). Each phase is buildable and demoable on its own.

## Phase 1 — MVP (smallest thing that proves the idea)

**Goal:** an applicant can browse public jobs and apply. A company can register, buy a plan, and post jobs. An admin can manage companies and plans.

| Capability | Status |
|---|---|
| Three frontends (public / company / admin), basic UI | ✅ |
| Auth (email + password, email confirmation) for all 3 realms | ✅ |
| Company creates job posts (title, description, skills, deadline, applicant cap) | ✅ |
| Public visitor browses + filters jobs | ✅ |
| Applicant registers and applies (with resume upload to S3) | ✅ |
| Slot-capacity correctness (DB row lock + unique constraint) | ✅ |
| Stripe subscription plans + Checkout | ✅ |
| Subscription gate on job-post creation | ✅ |
| Email confirmation + transactional emails (Postmark) | ✅ |
| Local Docker Compose setup | ✅ |
| Deploy to Hetzner / DigitalOcean (single VPS) | ✅ |

Skip everything else. Resume parser is offline; chat is offline; reports are offline; FCM is offline.

This phase should take weeks, not months.

## Phase 2 — Async + Notifications

**Goal:** the system reacts to events properly.

| Capability | Status |
|---|---|
| Laravel Horizon + Redis queues | ✅ |
| Notification Service skeleton (in-app + email) | ✅ |
| Bulk email broadcasts (chunked) | ✅ |
| FCM push notifications for new applications | ✅ |
| Subscription expiry sweeper + reminders | ✅ |
| Job auto-close sweeper | ✅ |
| Counter flush (Redis → Postgres) | ✅ |
| Sentry error tracking integrated | ✅ |

Now the platform feels alive — emails arrive, notifications fire, things expire correctly.

## Phase 3 — Resume Parsing + Skill Matching

**Goal:** applicants are auto-ranked by skill match.

| Capability | Status |
|---|---|
| Python + FastAPI Resume Parser service | ✅ |
| Event-driven flow (apply → emit → parse → write back) | ✅ |
| Skill dictionary table + admin UI to manage skills | ✅ |
| Match score visible to companies, applicants ranked | ✅ |
| Graceful failure (companies still see resumes if parse fails) | ✅ |

## Phase 4 — Realtime Chat

**Goal:** admins, companies, and (subscribed) companies can chat with file sharing.

| Capability | Status |
|---|---|
| Laravel Reverb running, auth tokens, private channels | ✅ |
| One-to-one conversations | ✅ |
| File attachments via S3 + presigned URLs | ✅ |
| ClamAV virus scanning of attachments | ✅ |
| Read receipts, presence, typing indicators | ✅ |

## Phase 5 — Search + Caching

**Goal:** search is fast and the system survives traffic spikes.

| Capability | Status |
|---|---|
| Meilisearch indexing (event-driven sync) | ✅ |
| Filtered search (skills, location, salary, dates) | ✅ |
| Cache layer for hot pages (job listings, single job) | ✅ |
| CDN configured for public pages and images | ✅ |
| Stampede protection on hot keys | ✅ |

## Phase 6 — Reports + Admin

**Goal:** the platform is operable.

| Capability | Status |
|---|---|
| Admin dashboard with key metrics | ✅ |
| Daily / weekly / monthly automated reports | ✅ |
| Bulk admin actions (announcements, suspending companies, etc.) | ✅ |
| Audit logs for admin actions | ✅ |

## Phase 7 — Production Hardening

**Goal:** ready to handle real load.

| Capability | Status |
|---|---|
| Move from VPS → Kubernetes (managed: EKS/DOKS) | ✅ |
| Read replicas for Postgres | ✅ |
| Loki + Prometheus + Grafana running | ✅ |
| OpenTelemetry instrumentation | ✅ |
| ArgoCD GitOps deployment | ✅ |
| Rate limiting at gateway | ✅ |
| WAF rules at Cloudflare | ✅ |
| Disaster recovery tested (restore DB into a fresh region) | ✅ |
| Load test with k6 (10K concurrent apply attempts) | ✅ |

## Phase 8 — Scale-out (only when forced)

**Goal:** services that need to scale separately, do.

| Capability | Status |
|---|---|
| Notification Service split out from Core API | ✅ |
| Payment Service split out from Core API | ✅ |
| Multi-region S3 replication for resumes | ✅ |
| Reverb cluster expansion (or migrate to Centrifugo if hitting limits) | ✅ |

Don't do these until production load makes you. Premature splitting wastes weeks.

---

## What NOT to build (now or maybe ever)

- A mobile app for v1 (responsive web first; mobile when there's user demand)
- AI / LLM features (resume summarization, chatbot) — add later when there's product-market fit
- Analytics tooling beyond admin reports — until needed
- Multi-language UI — until you have international users
- White-label custom domains for companies — until enterprise customers ask
- Voice/video chat — completely out of scope
- Social login (Google, LinkedIn) — nice-to-have, not blocking
- Saved searches, job alerts via email — Phase 9 if ever
- Recruiter-side ATS features (interview scheduling, etc.) — separate product

## Demo-able checkpoints

After every phase, you should be able to **demo the full flow** to someone (or record a video of it). If you can't demo, you have shipped something you can't show — and likely something the user can't use.

## Phase pace

Don't time-box. Each phase is "done when it works end-to-end and is observable in production". Aim for **one phase shipped per sprint cycle** if working solo at intermediate intensity.

After Phase 2, you have a real, deployable, paying-customer-capable product. Everything from Phase 3 onward is value-add. Don't skip phases. Don't build Phase 5 before Phase 2.

## Career signaling along the way

Each phase is also a **portfolio milestone**:

| Phase | What it signals to interviewers |
|---|---|
| 1 | "I can ship a full-stack product with payments and deploy it" |
| 2 | "I understand async work, queues, scheduled jobs" |
| 3 | "I've integrated a Python service into a Laravel system" |
| 4 | "I can build realtime systems with file uploads" |
| 5 | "I understand caching and search at scale" |
| 6 | "I can build admin tools real businesses use" |
| 7 | "I can run a production-grade Kubernetes deploy with observability" |
| 8 | "I understand microservice splitting trade-offs" |

By Phase 7 you're a strong fit for senior backend / DevOps roles in your job list.
