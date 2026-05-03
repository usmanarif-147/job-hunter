# CDN & Edge Compute

> A **CDN** (Content Delivery Network) caches your static assets at servers around the world close to users. **Edge compute** runs your code at those edge servers — fast, cheap, location-aware.

---

## 1. Cloudflare — Popularity: 10/10

**Purpose:**
Global CDN + DDoS protection + free SSL + DNS + WAF. Generous free tier (literally free for most personal/SMB use). Cloudflare Workers (edge compute), Pages (static hosting), R2 (S3-compatible storage with no egress fees), D1 (SQLite at edge), KV (key-value store at edge).

**Alternatives:** (see below)

**When to learn it:** beginner — easiest free win for any deployed project.

---

## 2. AWS CloudFront — Popularity: 9/10

**Purpose:**
AWS-managed CDN. Tightly integrated with S3, ALB, API Gateway. Lambda@Edge and CloudFront Functions for edge compute. Pay-per-GB.

**When to learn it:** intermediate — for AWS roles.

---

## 3. Fastly — Popularity: 7/10

**Purpose:**
Premium programmable CDN. Strong on instant cache invalidation and VCL (Varnish Configuration Language). Used by The New York Times, Stripe, GitHub. Expensive.

**When to learn it:** advanced — only at high-traffic premium sites.

---

## 4. Akamai — Popularity: 7/10

**Purpose:**
The original CDN giant. Enterprise-focused. Less popular for new projects; common in big media/banking.

**When to learn it:** advanced — only in large enterprises.

---

## 5. Bunny CDN — Popularity: 5/10

**Purpose:**
Cheap, modern CDN. Solid performance for the price. Indie/small-business favorite.

**When to learn it:** beginner — cost-effective alternative.

---

## 6. KeyCDN / StackPath / CDN77 — Popularity: 3–4/10

**Purpose:**
Smaller CDN providers. Niche.

---

## 7. Vercel — Popularity: 8/10

**Purpose:**
Hosting platform built on AWS + Cloudflare-style edge. Optimized for Next.js (built by same company). Edge Functions, Edge Middleware. Free tier for personal projects.

**When to learn it:** intermediate — for frontend-heavy projects.

---

## 8. Netlify — Popularity: 7/10

**Purpose:**
Similar to Vercel. Static site hosting + serverless functions + edge. Great DX.

**When to learn it:** intermediate — alternative to Vercel.

---

## Edge Compute Tools (a separate but related layer)

| Tool | Popularity | Purpose |
|---|---|---|
| **Cloudflare Workers** | 10/10 | JS/TS/Rust at the edge; massive free tier |
| **Vercel Edge Functions** | 8/10 | JS/TS at edge; tied to Vercel |
| **Netlify Edge Functions** | 6/10 | JS/TS at edge; tied to Netlify |
| **AWS Lambda@Edge** | 7/10 | Lambda at CloudFront points-of-presence |
| **Deno Deploy** | 5/10 | Deno runtime at edge |
| **Fastly Compute@Edge** | 5/10 | Wasm-based edge compute |

---

## Concepts to Master

- **Origin** — your actual server; CDN sits between client and origin
- **Cache hit / miss** — when CDN serves from its cache vs fetches from origin
- **TTL** — how long the CDN keeps a cached response
- **Cache-Control headers** — `public`, `private`, `max-age`, `s-maxage`, `no-cache`, `immutable`
- **Cache invalidation** — purging stale content; "two hardest things in CS"
- **Edge / PoP (Point of Presence)** — physical locations where CDN servers live
- **Cold cache vs warm cache** — first request slow, subsequent ones fast
- **Static vs dynamic content** — CDNs love static; dynamic needs careful headers
- **DDoS protection** — Cloudflare/AWS Shield absorb attacks
- **WAF (Web Application Firewall)** — block SQL injection, XSS at the edge
- **Bandwidth costs / Egress fees** — moving data out of clouds is expensive (R2, Backblaze, Bunny avoid this)
