# Feature Flags & Experimentation

> Toggle features on/off in production without redeploying. Roll out gradually (1% → 10% → 100%), A/B test, kill switch when something breaks. Decouples deploy from release.

---

## 1. LaunchDarkly — Popularity: 9/10

**Purpose:**
The market-leading SaaS for feature flags. Real-time flag evaluation, targeting rules (by user, region, etc.), experiments, audit logs. Expensive at scale.

**Alternatives:** (see below)

**When to learn it:** intermediate — recognise it; use a free alternative for personal projects.

---

## 2. GrowthBook — Popularity: 7/10

**Purpose:**
Open-source feature flags + A/B testing platform. Self-hostable or SaaS. Strong stats engine; Bayesian experimentation.

**When to learn it:** intermediate — best free option with experimentation.

---

## 3. Unleash — Popularity: 7/10

**Purpose:**
Open-source feature flag platform. Self-hostable. Mature, multi-language SDKs. Generous free tier.

**When to learn it:** intermediate — alternative to GrowthBook.

---

## 4. Flagsmith — Popularity: 6/10

**Purpose:**
Open-source feature flags + remote config. Self-host or SaaS. Decent free tier.

**When to learn it:** intermediate — alternative.

---

## 5. Statsig — Popularity: 7/10

**Purpose:**
SaaS feature flags + experimentation + analytics. Built by ex-Facebook engineers. Generous free tier (up to high event volumes).

**When to learn it:** intermediate — modern competitor to LaunchDarkly.

---

## 6. Split.io — Popularity: 6/10

**Purpose:**
SaaS feature flags + experimentation. Strong on experiment statistics. Common in mid-large companies.

**When to learn it:** intermediate — recognise it.

---

## 7. PostHog (feature flags) — Popularity: 7/10

**Purpose:**
PostHog is product analytics + session replay + feature flags + experiments — all in one. Self-hostable, generous free SaaS tier. Trending fast.

**When to learn it:** intermediate — strong all-in-one for startups.

---

## 8. Laravel Pennant — Popularity: 5/10 (in Laravel ecosystem)

**Purpose:**
Laravel's official feature flag package. Stores flags in DB or array driver. Simple, native, but no UI/experimentation features.

**When to learn it:** beginner — quick win for Laravel apps.

---

## What Feature Flags Actually Solve

- **Trunk-based development** — merge to main daily, hide unfinished features behind flags
- **Gradual rollouts** — release to 1% → 10% → 100% to catch issues early
- **A/B testing** — measure variant A vs variant B conversion
- **Kill switches** — turn off broken features without redeploying
- **Per-user / per-tenant features** — premium features only for paid plans
- **Internal beta** — flag enabled only for `@yourcompany.com` emails
- **Geographic rollouts** — features only in certain countries
- **Long-running migrations** — slowly route reads to a new system

---

## Concepts to Master

- **Boolean flags** — on/off
- **Multivariate flags** — A/B/C/...
- **Targeting rules** — based on user attributes, segments
- **Sticky bucketing** — same user always sees same variant
- **Flag debt** — old flags that should be removed (real maintenance burden)
- **Flag governance** — naming, ownership, expiration dates
- **Statistical significance** — when can you trust an A/B test?
- **Guardrail metrics** — secondary metrics to detect harm
