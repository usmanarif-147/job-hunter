# 17 — Team Workflow (Multi-Developer Collaboration)

## The setup

- **Code hosting:** GitHub (one organization, one repo per service)
- **Issue tracking:** GitHub Issues + GitHub Projects (kanban board)
- **Code review:** GitHub Pull Requests (PRs)
- **CI / CD:** GitHub Actions
- **Container registry:** GitHub Container Registry (GHCR)
- **Communication:** Slack or Discord (one channel per service / topic)
- **Documentation:** GitHub Wiki + this `project-roadmap/` folder

## Repo layout

For v1, **one repo per service** (polyrepo). Each service has its own deploy cadence and CI. Shared internal libraries (e.g., shared TypeScript types for frontend) live in their own packages.

| Repo | Contents |
|---|---|
| `core-api` | Laravel Core API |
| `chat-service` | Laravel Reverb |
| `notification-service` | Laravel notifications |
| `resume-parser` | Python FastAPI |
| `payment-service` | Laravel + Cashier |
| `web-public` | Nuxt — public site |
| `web-company` | Vue — company panel |
| `web-admin` | Vue — admin panel |
| `infra` | Terraform / Helm charts / K8s manifests |
| `docs` | This roadmap; system design notes |

A monorepo (one big repo) is also valid. Pick polyrepo when teams own services independently; pick monorepo when changes often span services. For v1 with 1–3 devs, polyrepo is simpler.

## Branching strategy — Trunk-based development

- One long-lived branch: `main`
- Every change goes through a short-lived feature branch: `feat/...`, `fix/...`, `chore/...`
- Branches live <2 days. Long branches = merge hell.
- Merging requires **PR + at least 1 review + green CI**

This is simpler than Git Flow and matches modern industry practice.

## PR rules (enforced by GitHub branch protection)

A PR cannot merge unless:

- [x] At least 1 reviewer approves
- [x] All CI checks pass (tests, linter, security scan)
- [x] No unresolved review comments
- [x] Branch is up-to-date with `main`

For solo work, the second reviewer rule is relaxed — but CI must still pass.

## What CI runs on every PR

Pseudocode for `.github/workflows/ci.yml` per service:

1. Checkout
2. Install deps
3. Run linter (PHP CS Fixer / ESLint / black)
4. Run static analysis (PHPStan / Psalm / mypy)
5. Run unit + feature tests (Pest / PHPUnit / Jest / pytest)
6. Run security scan (Trivy on Docker image; npm audit / composer audit)
7. Build Docker image (push only on `main` merges)

Goal: every CI run < 5 minutes. Slower than that → developers stop trusting CI.

## What CI runs after merging to `main`

1. Build a tagged Docker image (`v0.42.0`)
2. Push to GHCR
3. Update Helm/K8s manifests in the `infra` repo (PR auto-opened by a bot)
4. ArgoCD syncs the cluster on the next poll

This is the **GitOps** pattern — the cluster is whatever Git says it should be.

## Code review checklist

For reviewers:

- [ ] Does it solve the actual problem in the ticket?
- [ ] Are there tests covering the new behavior?
- [ ] Are edge cases (slot capacity, idempotency, auth) handled?
- [ ] Are there obvious performance issues (N+1 queries, no cache for hot path)?
- [ ] Are migrations backwards-compatible?
- [ ] Does it follow the codebase style?
- [ ] Are secrets / env vars handled correctly (no hard-coded keys)?
- [ ] Is there observability (logs, metrics) for the new code?

## Conventional commits

Commit messages follow:

```
type(scope): short summary

longer body if needed
```

Types: `feat`, `fix`, `chore`, `refactor`, `docs`, `test`, `perf`. Helps auto-generate changelogs and version bumps.

## Versioning

Semantic versioning per service: `MAJOR.MINOR.PATCH`. Bump:

- `MAJOR` for breaking API changes
- `MINOR` for new features (backwards-compatible)
- `PATCH` for bug fixes

Tools like `release-please` automate this from commit messages.

## Pre-commit hooks (developer machines)

Install **pre-commit** (Python tool) or **lefthook** (Go tool) — runs:

- Linter
- Static analyzer
- Trailing whitespace cleaner
- Secrets scanner (`gitleaks`)

Catches obvious issues before pushing → CI saves time.

## Handling cross-service changes

A feature that touches Core API + frontend:

1. Open a PR in `core-api` with the new endpoint behind a feature flag
2. Merge it (deploy doesn't activate the feature yet — flag is off)
3. Open a PR in `web-public` that uses the new endpoint
4. Merge after testing
5. Toggle the feature flag on for some users → ramp up

Feature flags (`spatie/laravel-feature-flag` or LaunchDarkly free) decouple deploy from release.

## Migrations across services

When a service adds a column or a new event field:

- **Backwards-compatible first**: add the column / field as optional
- Deploy and let other services adapt
- Then remove the old behavior in a separate deploy

**Never** rename or drop something in a single deploy; that breaks running pods during rollout.

## Onboarding a new developer

A new dev should be able to:

1. Clone the relevant repo
2. Run `docker compose up -d`
3. Be running locally within 30 minutes

If they can't, the onboarding docs are wrong — fix them.

The `README.md` of each repo must include:

- What the service does
- How to run it locally
- How to run tests
- How to make a PR
- Pointer to this `project-roadmap/` for system context

## Slack / Discord conventions

- One channel per service for discussion
- One channel `#alerts` where automated alerts land
- One channel `#incidents` for active production issues — async-first, but can convert to a video call when needed
- One channel `#standup` for daily updates if doing sync standups

## Definition of Done

A feature is done when:

- Code is merged
- Tests are green
- Deployed to production
- Verified in production (smoke test)
- Observability is in place (logs/metrics for the new behavior)
- Documentation is updated
- Stakeholder confirms it works

Anything less, and we're shipping debt.
