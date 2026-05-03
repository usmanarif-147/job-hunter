# 09 — Notifications (FCM, Email, In-App)

## Three delivery channels, one service

The Notification Service is one Laravel app with queue workers. It receives events ("ApplicationCreated", "JobExpired", etc.) from the event bus, decides who should be told, picks the right channel(s), and dispatches.

```
Event bus (Kafka / Redis Streams)
            │
            ▼
   Notification Service
   ┌────────┴────────┐
   ▼        ▼        ▼
  FCM    Email     In-app
 (push)  (SMTP    (DB row +
        / SES)    badge count)
```

## Channels

| Channel | When to use | Provider |
|---|---|---|
| **FCM push** | New application, new chat message, time-sensitive alerts | Firebase Cloud Messaging |
| **Email — transactional** | Account confirmation, password reset, single-event notifications | Postmark (high deliverability) |
| **Email — bulk / broadcast** | Admin announcements, recruiter mass-emails (1–1000 recipients) | AWS SES (cheap volume) |
| **In-app** | Bell-icon dropdown in the UI | Stored in `notifications` table |

## Routing — who gets what

Each user has notification preferences (per-channel, per-event-type). Defaults:

| Event | Admin | Company users | Applicants |
|---|---|---|---|
| New application | — | FCM + email + in-app | — |
| Application status change | — | — | Email + in-app |
| Subscription expiring | — | Email | — |
| Platform announcement | Email | Email | Email (only critical) |
| New chat message | FCM | FCM | — |

Preferences live in the `notification_preferences` table.

## Fan-out — the multi-recipient case

When an applicant applies to a job, the company may have **many users** with the role "receive applications". For each:

1. Look up their FCM device tokens (a user can have multiple devices)
2. Look up their email
3. Build a single batched FCM payload and send (FCM supports up to 500 tokens per call)
4. Email is queued separately (one job per email; let the queue parallelise)

Batching FCM saves cost and avoids rate limits.

## Queueing & retries

- Every notification is a queued job (Laravel queues + Horizon)
- **Retries:** 3 attempts with exponential backoff (10s, 1min, 5min)
- **Dead-letter queue:** after final failure, the job goes to `notifications_failed` for inspection
- **Idempotency:** every notification has a `notification_id` (UUID); if a worker crashes mid-send, retrying re-sends is fine because providers deduplicate by message-id

## Bulk email broadcast (admin / company sending 1–1000 emails)

Sending 1000 emails inline would time out. The flow:

1. **Sender** creates a `BroadcastJob` (subject, body template, recipient list, send_at)
2. The Notification Service splits the recipient list into **chunks of 50**
3. Each chunk is queued as a separate job
4. Workers pull chunks and send via SES — many in parallel
5. Each chunk reports its success/failure rates back to the `BroadcastJob` row
6. Admin/company sees a progress bar updating live (via Reverb, optional)

This pattern means a 1000-recipient broadcast finishes in under a minute, not an hour.

## Email deliverability essentials

- SPF, DKIM, DMARC records on `job-hunter.com`
- Separate sending domains: `notify.job-hunter.com` (transactional) vs `marketing.job-hunter.com` (bulk) — protects transactional reputation
- Bounce + complaint webhooks from SES → automatically suppress addresses that bounce hard
- Every marketing email has a one-click unsubscribe link

## FCM — device token management

- Mobile/web apps register their FCM token on login
- Tokens stored in `device_tokens` table (user_id, token, platform, last_used_at)
- On send failure (`InvalidRegistration`, `NotRegistered`), token is deleted — no silent zombie tokens
- Token refresh handled by the app SDK; the app re-registers when it changes

## In-app notifications

- A row in the `notifications` table per user per event
- Frontend polls `/notifications/unread-count` every 30 seconds, or
- Frontend subscribes to a Reverb channel `private-user.{id}` and gets pushed updates (preferred)
- Marking-as-read is a single API call

## Rate limits & abuse prevention

- A company can broadcast **at most 5,000 emails per day** (default plan), enforced at the Notification Service
- Higher limits available on enterprise plans
- Per-IP rate limits on the API gateway prevent abuse
- All bulk emails get a sample sent to a "preview" address first so the admin can review

## Observability

- Every notification logs: `event_id`, `user_id`, `channel`, `provider`, `status`, `latency`
- Dashboard in Grafana: send rate, delivery rate, bounce rate by domain, queue depth
- Alert if delivery rate drops below 95% for any channel
