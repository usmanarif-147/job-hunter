# 07 — Job Application Flow & The Slot-Capacity Race Condition

This is the most important correctness chapter. Read it twice.

## The problem

A job has `max_applicants = 100`. 99 people have already applied. Then **300 users hit "Apply" within the same second**. Without the right design, multiple of them succeed → the job is overbooked. Recruiter angry, system broken.

This is a classic **race condition**. It is invisible in development (single user, single request) and only appears under load — i.e., in production.

## Why naive code fails

Pseudocode of the wrong way:

```
1. Read current applicant_count  (99)
2. If < max_applicants:
3.    Insert application
4.    Increment applicant_count
```

300 users do step 1 simultaneously. All read 99. All pass step 2. All insert. Counter reaches 399. Disaster.

## Three real solutions, ranked

### Solution A — Database row lock (default for v1)

Wrap the apply action in a database transaction with `SELECT ... FOR UPDATE` on the job row.

```
BEGIN;
SELECT applicant_count, max_applicants FROM jobs WHERE id = ? FOR UPDATE;
-- 👆 this row is now locked. Other transactions wait.
IF applicant_count >= max_applicants THEN RAISE 'job_full'; END IF;
INSERT INTO applications (...);
UPDATE jobs SET applicant_count = applicant_count + 1 WHERE id = ?;
COMMIT;
```

Postgres serializes the locks. The 100th applicant gets in, the 101st gets a `job_full` error. Clean.

**Pros:** Correct, simple, ACID, works without extra infra.
**Cons:** All writes for a hot job go through one row's lock. If 300 people apply simultaneously, they queue. That's fine — apply is not on the latency-critical UX path; a 1-second wait for them is acceptable.

### Solution B — Redis atomic counter (when row locks become a bottleneck)

Use a Redis `INCR` as the source of truth for "how many applications are in flight." Postgres still stores the rows, but the slot reservation happens in Redis first.

```
1. INCR job:{id}:applicant_count  → returns new value
2. If new value > max_applicants: DECR back, reject.
3. Else: insert into Postgres asynchronously.
```

`INCR` is atomic. No race possible. Can handle hundreds of thousands of attempts per second per key.

**Pros:** Fast, scales hugely.
**Cons:** Now two systems hold state — must reconcile (cron job that counts Postgres rows and corrects Redis if they drift). Slightly more complex.

### Solution C — Database unique constraint as a safety net (always do this)

Add a unique constraint on `(job_id, applicant_id)`. This guarantees a single applicant cannot duplicate-apply even if the application logic has a bug. **Combine with A or B**, never replace.

## Our chosen approach

- **v1 (MVP):** Solution A (`FOR UPDATE`) + Solution C (unique constraint)
- **v2 (when load justifies):** Move counter to Redis (Solution B), keep Postgres as audit log

## The full lifecycle of an application

1. **User clicks Apply on the job page**
2. **Frontend** sends `POST /jobs/{id}/apply` (with optional resume upload — see step 7)
3. **Core API**:
   - Verifies user is logged in
   - Verifies job is still open and not full *(transactional check, see Solution A)*
   - Inserts the application row
   - Emits `ApplicationCreated` event to Kafka / Redis Streams
4. **Search Service** (consumer) updates its denormalized counter
5. **Notification Service** (consumer):
   - Resolves which company users want notifications for this job
   - Fans out FCM pushes (one per device)
   - Queues emails
6. **Resume Parser Service** (consumer):
   - Picks up the resume from S3
   - Extracts text + skills
   - Computes a match score against the job's required skills
   - Writes parsed result back to Core API (or a `parsed_resumes` table)
7. **User** sees confirmation "Application received"

Steps 4–6 are async — they don't block the user's response.

## Auto-expire jobs (related concern)

A job closes when **either** of these is true:
- `apply_before` deadline has passed
- `applicant_count >= max_applicants`

Two ways to enforce closure:

1. **At read time**: when listing or fetching a job, compute "is open?" on the fly. Cheap.
2. **At write time** (a sweeper): a scheduled job runs every minute, finds jobs that should be closed, marks them `status = closed`, and notifies the company. This makes "closed at" explicit and makes analytics easier.

Do both. Read-time check is the safety net; sweeper makes the data clean.

## Idempotency

Network glitches happen. The frontend may retry an apply call. Without idempotency, the same user could create two applications.

- The unique constraint on `(job_id, applicant_id)` covers this for applications
- For other write endpoints, accept an `Idempotency-Key` header from the client and store the result keyed by it (Redis with 24-hour TTL)

## Failure & retry behavior

| Step | If it fails |
|---|---|
| Slot lock fails | Return 409 "Job is full" — the user can pick another job |
| Insert application succeeds, event emit fails | Outbox pattern: write the event into an `outbox` table inside the same DB transaction; a separate worker drains the outbox into Kafka. Guarantees no lost events. |
| FCM push fails | Notification service retries with exponential backoff (3, 9, 27 seconds, then dead-letter) |
| Resume parser fails | Retry 3x; on final failure, mark application as `parse_failed` and let the company see the raw resume |

## Key takeaway

The system is **eventually consistent everywhere except the slot count**. The slot count must be **strictly consistent** — over-applying ruins user trust. Everything else can lag a few seconds.
