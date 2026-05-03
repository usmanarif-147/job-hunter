# Secret Management

> Where you store API keys, database passwords, certificates, OAuth tokens, encryption keys — anything that should never be in Git. Rotated, audited, accessed by least-privilege rules.

---

## 1. HashiCorp Vault — Popularity: 9/10

**Purpose:**
Industry-standard secret store. Stores static secrets, generates dynamic secrets (e.g., on-demand DB credentials), encryption-as-a-service, certificate authority. Self-hosted or managed (HCP Vault). Steep learning curve.

**Alternatives:** (see below)

**When to learn it:** advanced — heavyweight but powerful.

---

## 2. AWS Secrets Manager — Popularity: 9/10

**Purpose:**
AWS-managed secret store. Auto-rotation (especially for RDS credentials), IAM-based access, KMS encryption. Charges per secret + per API call.

**When to learn it:** intermediate — for AWS-heavy roles.

---

## 3. AWS Systems Manager Parameter Store — Popularity: 8/10

**Purpose:**
Cheaper AWS alternative to Secrets Manager. Stores parameters and SecureString secrets encrypted with KMS. No auto-rotation but free for standard parameters.

**When to learn it:** intermediate — common cost-effective AWS choice.

---

## 4. Doppler — Popularity: 6/10

**Purpose:**
SaaS secret manager. Beautiful UI, good for small/mid teams. Syncs secrets to AWS, Heroku, Vercel, GitHub Actions, etc.

**When to learn it:** intermediate — devex-friendly choice.

---

## 5. Sealed Secrets (Bitnami) — Popularity: 6/10

**Purpose:**
K8s controller that lets you commit **encrypted secrets** to Git. Cluster decrypts them at runtime. Solves "we want GitOps but don't want plaintext secrets in Git."

**Alternatives:**
- **External Secrets Operator (ESO)** — 7/10 — Pulls secrets from Vault/AWS/etc. into K8s Secrets at runtime.
- **SOPS (Mozilla)** — 6/10 — Encrypts files using KMS/PGP/age; popular for IaC repos.

**When to learn it:** advanced — for K8s + GitOps teams.

---

## 6. Google Secret Manager — Popularity: 7/10

**Purpose:**
GCP-managed secret store. Equivalent of AWS Secrets Manager.

**When to learn it:** intermediate — for GCP roles.

---

## 7. Azure Key Vault — Popularity: 7/10

**Purpose:**
Azure-managed secret + key + certificate store.

**When to learn it:** intermediate — for Azure roles.

---

## 8. 1Password Secrets Automation / Bitwarden — Popularity: 5/10

**Purpose:**
Password managers that also expose APIs/CLIs for secret distribution. Useful for small teams who already use them for human passwords.

**When to learn it:** beginner — quick win for small teams.

---

## 9. Infisical — Popularity: 5/10

**Purpose:**
Open-source secret management platform; modern UI, self-hostable. Newer player.

**When to learn it:** beginner — alternative to Doppler.

---

## Concepts to Master

- **Never commit secrets to Git** — even in private repos; use `.gitignore`
- **Rotation** — change secrets periodically; automate it
- **Auditing** — log every read; know who accessed what
- **Least privilege** — only the service that needs a secret can read it
- **Encryption at rest vs in transit** — both matter
- **KMS** — Key Management Service; the master key that encrypts other keys
- **Dynamic secrets** — Vault can generate a 1-hour-valid DB password on demand
- **Secrets in environment variables** — common but visible in `/proc`; alternatives: files, mount points
- **Secrets in CI** — GitHub Actions secrets, GitLab CI variables, etc.
- **Detected leaks** — `git-secrets`, `truffleHog`, `gitleaks` scan for committed secrets
