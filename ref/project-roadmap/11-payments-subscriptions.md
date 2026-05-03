# 11 — Payments & Subscriptions

## What this service handles

- Plans (created by super admin) — e.g., Starter / Pro / Enterprise
- A company subscribing to a plan via Stripe Checkout
- Recurring charges (Stripe handles this)
- Webhooks: invoice paid, payment failed, subscription cancelled
- Gating "post a job" behind an active subscription
- Expiry sweeper — disable jobs after subscription lapses

## Why a (sort of) separate service

Payments must be **bullet-proof**: idempotent, retryable, auditable. Isolating the payment code (even as a separate Laravel module / namespace) keeps the rule-heavy logic out of the busy main API codebase.

For v1 it can be a module inside the Core API; for production, splitting it into its own deployable Laravel service is cheap insurance.

## Stack

- **Stripe** — the payment provider (mandatory; we don't roll our own)
- **Laravel Cashier (Stripe driver)** — handles 90% of subscription mechanics
- **Stripe Webhooks** — Stripe calls our service when events happen

## Plans — owned by super admin

Plans live in **Stripe** as the source of truth. We mirror them into our `subscription_plans` table for fast reads and to attach our own metadata (job-post-quota, can-chat-with-other-companies, etc.).

When admin creates a plan:

1. Admin fills the form (name, price, interval, features)
2. Service calls Stripe API to create the Product + Price
3. Service inserts a row in `subscription_plans` with the Stripe IDs
4. Admin can later sync from Stripe (one-way pull) to fix drift

## Subscription flow (company perspective)

1. Company picks a plan
2. Service calls Stripe to create a **Checkout Session** (hosted by Stripe)
3. Company is redirected to Stripe → enters card → completes
4. Stripe redirects back to `success_url`
5. Stripe also sends a webhook (`checkout.session.completed`)
6. Webhook handler creates a `subscriptions` row + grants the company access

We rely on the **webhook**, not the redirect, as the signal of success — redirects can be lost (closed tab, network hiccup), webhooks retry.

## Webhook handling — the critical piece

Stripe webhooks must be:

- **Verified** — every webhook has a signature (`Stripe-Signature` header); reject if invalid
- **Idempotent** — Stripe may deliver the same event twice; we keep a `webhook_events` table with the Stripe event ID and `UNIQUE` constraint, dropping duplicates
- **Fast** — return 200 within 5 seconds; do heavy work in a queued job
- **Replayable** — store the raw body; if our handler has a bug, we can replay events later

Events to handle:

| Event | Action |
|---|---|
| `checkout.session.completed` | Activate subscription |
| `invoice.paid` | Extend access; record invoice |
| `invoice.payment_failed` | Mark `past_due`; trigger dunning email |
| `customer.subscription.deleted` | Mark expired; schedule job-post disablement |
| `customer.subscription.updated` | Sync plan / quantity / status |

## Gating writes

The Core API's job-post creation endpoint checks: **does this company have an active subscription?** If no, return 402 (Payment Required) with a redirect to the billing page.

The check is a simple lookup against the `subscriptions` table; cached in Redis for 60 seconds.

## Expiry sweeper

A scheduled job runs **every hour**:

1. Find subscriptions where `ends_at < NOW()` and status != `expired`
2. Mark them `expired`
3. Disable all open job posts owned by that company (don't delete — let recruiter reactivate after re-subscribing)
4. Send a "your subscription expired" email
5. Send reminders **7 days, 3 days, 1 day before** expiry too (separate scheduled job filters by upcoming `ends_at`)

This sweeper is also resilient — if it misses a run (the worker crashed), the next run picks up everything overdue.

## Refunds & chargebacks

- Refunds: admin-initiated via Stripe dashboard; webhook `charge.refunded` updates our records
- Chargebacks: handled outside the app; admin sees a notification

## Currency & tax

- v1: USD only, prices stored in cents
- Tax: defer until needed; Stripe Tax can be enabled later
- Pakistan-specific: Stripe doesn't directly support PK businesses → consider **Paddle** as merchant-of-record (handles tax + currency conversion) if launching from PK

## Quotas (an alternative to "unlimited" plans)

If a plan has a quota (e.g., 10 active job posts), the post-creation endpoint:

1. Counts the company's currently-active job posts
2. Compares to plan quota
3. Allows or rejects

The count is cached (`company:{id}:active_jobs` in Redis) and invalidated whenever a job is created/closed.

## Failure modes

| Failure | What happens |
|---|---|
| Stripe webhook not received | Stripe auto-retries for 3 days |
| Our service is down when Stripe webhook arrives | Stripe queues retries; we catch up when we recover |
| Webhook signature invalid | Reject with 400; alert (probably an attack) |
| Subscription state drifts from Stripe | Daily reconciliation job pulls subscription list from Stripe and corrects our DB |

## What we deliberately don't build

- Our own card form / PCI handling (use Stripe Checkout — they handle compliance)
- Refund logic in our UI (do it from Stripe's dashboard for v1)
- Promo code engine (use Stripe Coupons)
- Multi-currency (one currency until proven need)
