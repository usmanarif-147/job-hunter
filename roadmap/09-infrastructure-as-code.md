# Infrastructure as Code (IaC)

> Define cloud infrastructure (servers, networks, databases, DNS, IAM) in code files instead of clicking around web consoles. Code is version-controlled, reviewable, repeatable. Critical for any team beyond a few servers.

---

## 1. Terraform — Popularity: 10/10

**Purpose:**
HashiCorp's IaC tool. Declarative configs in HCL (HashiCorp Configuration Language). Multi-cloud (AWS, GCP, Azure, Cloudflare, ...). Tracks state in a `terraform.tfstate` file. The industry default.

**Alternatives:** (see below)

**When to learn it:** intermediate — highest IaC ROI; start here.

---

## 2. OpenTofu — Popularity: 7/10

**Purpose:**
Open-source fork of Terraform. Started after HashiCorp changed Terraform's license to BSL in 2023. CLI-compatible with Terraform. Backed by the Linux Foundation.

**When to learn it:** intermediate — if you know Terraform you basically know OpenTofu.

---

## 3. Pulumi — Popularity: 7/10

**Purpose:**
IaC using **real programming languages** (TypeScript, Python, Go, .NET, Java) instead of HCL. Better for complex logic, loops, and abstractions. Smaller community than Terraform.

**When to learn it:** advanced — strong choice for engineering-heavy teams.

---

## 4. Ansible — Popularity: 8/10

**Purpose:**
Configuration management tool — installs/configures software on existing servers (over SSH). Different category from Terraform: Terraform creates the VM, Ansible configures it. Agentless, idempotent, YAML-based.

**Alternatives:**
- **Chef** — 4/10 — Older, Ruby-based; declining.
- **Puppet** — 4/10 — Older, agent-based; declining.
- **Salt (SaltStack)** — 3/10 — Niche.

**When to learn it:** intermediate — useful for VPS-based deploys.

---

## 5. AWS CloudFormation — Popularity: 7/10

**Purpose:**
AWS-native IaC. JSON/YAML templates for AWS resources. Locked to AWS only. Fading vs Terraform but still common.

**Alternatives:**
- **AWS CDK (Cloud Development Kit)** — 7/10 — Write CloudFormation in real languages (TS, Python, Java).

**When to learn it:** intermediate — only for AWS-only shops.

---

## 6. Helm — Popularity: 9/10

**Purpose:**
Package manager for Kubernetes. A "chart" is a templated bundle of K8s manifests. `helm install postgres bitnami/postgresql` installs a full Postgres setup. Almost mandatory if you use K8s.

**Alternatives:**
- **Kustomize** — 8/10 — Built into kubectl; overlays/patches manifests instead of templating.

**When to learn it:** intermediate — pair with K8s.

---

## 7. Crossplane — Popularity: 5/10

**Purpose:**
Manage cloud resources from inside Kubernetes itself. Define a database as a K8s CRD; Crossplane provisions it on AWS/GCP. Niche but interesting.

**When to learn it:** advanced — for K8s-heavy ops teams.

---

## 8. Packer (HashiCorp) — Popularity: 5/10

**Purpose:**
Builds machine images (AMIs, Docker images, VMware templates) from a single config. Useful when you need pre-baked VM images.

**When to learn it:** advanced — for VM-based pipelines.

---

## Concepts to Master

- **Declarative vs imperative** — describe desired state, not steps
- **Idempotent** — running twice produces the same result
- **State file** — Terraform's record of what exists
- **Remote state** — store state in S3/Terraform Cloud, not your laptop
- **Modules** — reusable units of Terraform code
- **Drift detection** — when reality differs from code, who wins?
- **Plan / Apply / Destroy** — Terraform's three core commands
- **Providers** — Terraform plugins per cloud/service (AWS, GCP, Cloudflare, GitHub)
