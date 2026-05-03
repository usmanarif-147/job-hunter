# CI/CD Pipelines

> **CI** (Continuous Integration) = on every commit, run tests/build automatically. **CD** (Continuous Delivery/Deployment) = automatically deploy passing builds to staging/production. Eliminates manual deploy steps and catches bugs early.

---

## 1. GitHub Actions — Popularity: 10/10

**Purpose:**
CI/CD baked into GitHub. Define workflows in `.github/workflows/*.yml`. Free for public repos; generous free minutes for private. Massive marketplace of pre-built actions. The default for most modern projects.

**Alternatives:** (see below)

**When to learn it:** beginner — highest ROI; start here.

---

## 2. GitLab CI — Popularity: 9/10

**Purpose:**
CI/CD baked into GitLab. Defined in `.gitlab-ci.yml`. Strong if your code lives in GitLab; tight integration with merge requests, environments, and built-in container registry.

**When to learn it:** intermediate — if your team uses GitLab.

---

## 3. Jenkins — Popularity: 7/10

**Purpose:**
Granddaddy of CI/CD. Self-hosted, plugin-rich, runs anywhere. Configured via web UI or `Jenkinsfile` (Groovy DSL — "Pipeline as Code"). Still huge in enterprise but losing ground to cloud-native CI. **Free and open source — yes, you can run your own Jenkins for free.**

**When to learn it:** intermediate — you've touched it; deepen by running your own instance.

---

## 4. CircleCI — Popularity: 7/10

**Purpose:**
Cloud CI/CD SaaS. Configured via `.circleci/config.yml`. Fast, well-designed, generous free tier. Popular in startups before GitHub Actions caught up.

**When to learn it:** intermediate — only if your company uses it.

---

## 5. ArgoCD — Popularity: 8/10

**Purpose:**
GitOps deployment tool for Kubernetes. Continuously syncs the cluster's state with what's defined in a Git repo. "Git is the single source of truth." Declarative, auditable, easy rollbacks.

**Alternatives:**
- **Flux CD** — 7/10 — Same GitOps idea; CNCF graduated; lighter.

**When to learn it:** advanced — once you're comfortable with K8s.

---

## 6. Tekton — Popularity: 5/10

**Purpose:**
Kubernetes-native CI/CD framework (CRDs for Pipelines, Tasks). Building blocks rather than a finished product. Used in OpenShift Pipelines.

**When to learn it:** advanced — niche.

---

## 7. Drone CI — Popularity: 5/10

**Purpose:**
Container-native CI. Each pipeline step runs in a Docker container. Self-hosted, lightweight. Acquired by Harness.

**When to learn it:** advanced — niche.

---

## 8. Spinnaker — Popularity: 5/10

**Purpose:**
Multi-cloud continuous delivery platform from Netflix. Sophisticated deployment strategies (canary, blue/green, rolling). Heavy to operate.

**When to learn it:** advanced — only at large scale.

---

## 9. Travis CI — Popularity: 3/10

**Purpose:**
Once dominant for open-source CI. Fading after pricing changes; mostly legacy.

**When to learn it:** skip unless maintaining old projects.

---

## 10. AWS CodePipeline / CodeBuild — Popularity: 6/10

**Purpose:**
AWS-native CI/CD. Tight IAM integration, ties into ECS/EKS/S3 deployments. Less ergonomic than GitHub Actions but useful when you're all-in on AWS.

**When to learn it:** intermediate — for AWS-heavy roles.

---

## What a CI/CD Pipeline Typically Does

A typical "push to main" pipeline:

```
1. Checkout code
2. Install dependencies (composer install, npm ci)
3. Run linters (PHP CS Fixer, ESLint)
4. Run static analysis (PHPStan, Psalm)
5. Run unit + feature tests (Pest, Jest)
6. Run security scan (npm audit, Snyk, Trivy)
7. Build Docker image
8. Push image to registry (Docker Hub, ECR, GHCR)
9. Deploy:
   - SSH + docker pull on VPS, OR
   - kubectl apply on K8s, OR
   - aws ecs update-service, OR
   - ArgoCD picks up Git change
10. Run smoke tests against the deployed env
11. Notify Slack / Discord
```

## Concepts to Master

- **Workflow / Pipeline / Stage / Job / Step** — the execution hierarchy
- **Triggers** — push, pull-request, tag, schedule, manual
- **Matrix builds** — same job run with different parameters (PHP 8.2/8.3/8.4)
- **Caching** — speed up by caching dependencies between runs
- **Secrets** — store API keys/tokens securely in CI
- **Environments** — staging vs production gating
- **Rolling / Blue-Green / Canary** deployment strategies
- **Rollback** — how to undo a bad deploy fast
- **Artifacts** — files (built binaries, test reports) passed between jobs
