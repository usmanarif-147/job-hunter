# Authentication & Identity

> Tools and standards for verifying who a user is (authentication) and what they can do (authorization). Don't roll your own crypto — use proven libraries and providers.

---

## 1. Laravel Sanctum — Popularity: 9/10 (Laravel ecosystem)

**Purpose:**
Laravel's lightweight authentication for SPAs, mobile apps, and simple API tokens. Cookie-based for first-party SPAs; opaque tokens for APIs. Default for most Laravel APIs.

**Alternatives within Laravel:**
- **Laravel Passport** — 7/10 — Full OAuth2 server; heavier, for third-party API consumers.
- **Laravel Breeze** — 8/10 — Auth scaffold (login, register, password reset).
- **Laravel Jetstream** — 7/10 — Beefier scaffold (teams, 2FA).
- **Laravel Fortify** — 7/10 — Headless auth backend (the engine behind Breeze/Jetstream).

**When to learn it:** beginner — mandatory for Laravel APIs.

---

## 2. Auth0 — Popularity: 9/10

**Purpose:**
Industry-leading identity SaaS. Social login (Google, Apple, Facebook), enterprise SSO (SAML, OIDC), MFA, passwordless. Now part of Okta. Generous free tier (up to 7,000 users).

**Alternatives:** (see below)

**When to learn it:** intermediate — common in B2B SaaS.

---

## 3. Keycloak — Popularity: 8/10

**Purpose:**
Open-source identity provider from Red Hat. Self-hostable. Full-featured: OAuth2, OIDC, SAML, social login, user federation (LDAP/AD), admin UI. Heavy but powerful.

**When to learn it:** advanced — for self-hosted enterprise auth.

---

## 4. Okta — Popularity: 8/10

**Purpose:**
Enterprise identity giant (now owns Auth0). SSO, MFA, lifecycle management, directory. Used at large companies for workforce identity.

**When to learn it:** advanced — for enterprise SSO integrations.

---

## 5. AWS Cognito — Popularity: 7/10

**Purpose:**
AWS-managed identity. User Pools (your users) + Identity Pools (federated AWS access). Cheap at scale. UI/SDK quality is rough; integrations are AWS-flavored.

**When to learn it:** intermediate — for AWS roles.

---

## 6. Firebase Authentication — Popularity: 8/10

**Purpose:**
Google/Firebase's auth service. Easy SDKs for web/mobile, social login, phone auth. Free generous tier. Common in mobile-first apps.

**When to learn it:** beginner — fast win for mobile/SPA projects.

---

## 7. Supabase Auth — Popularity: 7/10

**Purpose:**
Auth as part of Supabase (open-source Firebase alternative). Built on PostgreSQL Row-Level Security. Magic links, social login, MFA. Self-hostable.

**When to learn it:** intermediate — modern alternative to Firebase.

---

## 8. Clerk — Popularity: 7/10

**Purpose:**
Modern auth SaaS with beautiful pre-built UI components. Strong DX for Next.js/React. Generous free tier.

**When to learn it:** intermediate — modern frontend-first option.

---

## 9. Ory (Kratos / Hydra / Keto) — Popularity: 5/10

**Purpose:**
Open-source identity stack. Kratos = users, Hydra = OAuth2 server, Keto = authorization. Composable, complex.

**When to learn it:** advanced — niche.

---

## 10. WorkOS — Popularity: 6/10

**Purpose:**
Modern SaaS for **enterprise SSO** (SAML, SCIM, directory sync). Built so startups can easily sell to enterprises that demand SSO.

**When to learn it:** advanced — for B2B SaaS startups going enterprise.

---

## Standards & Protocols to Master

| Standard | What it does |
|---|---|
| **OAuth 2.0** | Delegated authorization (third-party access without sharing password) |
| **OpenID Connect (OIDC)** | Identity layer on top of OAuth 2.0 — adds "who is the user?" |
| **SAML** | Enterprise SSO standard (XML-based); legacy but ubiquitous |
| **JWT (JSON Web Token)** | Self-contained signed token (claims inside) |
| **PASETO** | Modern, safer alternative to JWT |
| **Session cookies** | Classic stateful auth; still the safest default for web apps |
| **CSRF protection** | Stops cross-site form submissions; built into Laravel |
| **CORS** | Controls which origins can call your API |
| **MFA / 2FA** | TOTP (Authenticator app), WebAuthn (passkeys), SMS (avoid) |
| **WebAuthn / Passkeys** | Modern passwordless; replacing passwords industry-wide |
| **PKCE** | Proof Key for Code Exchange — secures OAuth on mobile/SPAs |
| **mTLS** | Mutual TLS — both client and server present certs |
| **API keys** | Static tokens; simplest API auth |
| **HMAC signing** | Sign requests with shared secret; common in webhooks |
| **RBAC vs ABAC** | Role-based vs Attribute-based access control |

---

## Authorization (separate from authentication)

| Tool | Notes |
|---|---|
| **Laravel Gates / Policies** | Built-in Laravel authorization |
| **Spatie laravel-permission** | Most-used Laravel roles/permissions package |
| **Cerbos** | Open-source policy engine (decoupled authz) |
| **OpenFGA** | Google Zanzibar–style fine-grained authz |
| **OPA (Open Policy Agent)** | General-purpose policy engine; CNCF graduated |
| **Permit.io** | Authz-as-a-service |
