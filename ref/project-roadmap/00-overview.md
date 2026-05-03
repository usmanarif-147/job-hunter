# 00 — Project Overview

## What we're building

A job-board platform like Glassdoor / Indeed / Rozee.pk. Three audiences:

| Audience | Domain | Role |
|---|---|---|
| Super admins | `admin.job-hunter.com` | Platform owners — manage companies, plans, reports, broadcasts |
| Companies | `company.job-hunter.com/{uuid}` | Buy a plan, post jobs, receive applications |
| Public / Applicants | `job-hunter.com` | Browse jobs, apply, save favourites |

## Goal

A **system**, not just a project. Built so it survives growth, multiple developers, and traffic spikes without rewrites.

## Scale targets

| Metric | Number |
|---|---|
| Total registered users | ~2 million |
| Average concurrent users | ~1,000 |
| Peak concurrent users | ~1 million |
| Critical concurrency | hundreds of users applying to the same slot-capped job at once |

## Non-functional priorities (in order)

1. **Correctness under concurrency** — never overbook a slot-capped job
2. **Resilience** — one service failing doesn't take the whole system down
3. **Observability** — we know what's happening in production at all times
4. **Developer velocity** — multiple devs can ship safely without stepping on each other
5. **Cost** — sensible defaults; scale up only what needs scaling

## What this folder contains

This folder has the **system design plan** for the platform. Files are numbered in reading order. Each file is **short, no code, design-only**. Implementation choices are stated; *how* to implement comes later.

## File index

| # | File | Topic |
|---|---|---|
| 01 | `01-actors-and-subdomains.md` | Three audiences and their domains |
| 02 | `02-architecture-overview.md` | The big picture |
| 03 | `03-microservices-breakdown.md` | Which services to split out, which to keep together |
| 04 | `04-tech-stack.md` | The chosen stack with one-line reasons |
| 05 | `05-databases.md` | DB choices and isolation per service |
| 06 | `06-api-gateway-and-auth.md` | Kong + auth flow + subdomain routing |
| 07 | `07-job-application-flow.md` | The slot-capacity race condition (the key correctness problem) |
| 08 | `08-realtime-chat.md` | WebSocket chat + file sharing |
| 09 | `09-notifications.md` | FCM + email + in-app notifications |
| 10 | `10-resume-parsing.md` | Async resume parsing + skill matching |
| 11 | `11-payments-subscriptions.md` | Stripe plans + expiry handling |
| 12 | `12-scheduled-jobs.md` | Cron, sweepers, reports |
| 13 | `13-file-storage.md` | Object storage, presigned URLs, file safety |
| 14 | `14-caching-and-search.md` | Where caching goes; how job search works |
| 15 | `15-scaling-strategy.md` | Handling 1M concurrent — what scales how |
| 16 | `16-observability.md` | Logs, metrics, traces, alerts |
| 17 | `17-team-workflow.md` | Git, code review, CI/CD for multiple developers |
| 18 | `18-local-dev-setup.md` | Running everything on localhost via Docker Compose |
| 19 | `19-production-deployment.md` | What changes when going to production |
| 20 | `20-security.md` | Top security concerns and mitigations |
| 21 | `21-rollout-phases.md` | Build order — MVP first, scale-out later |

## How to read

Read 00 → 06 to understand the system. Then read 07 (it's the most important correctness chapter). After that, jump to whichever capability you're about to build.
