# Web Servers & Load Balancers

> Software that accepts HTTP/TCP traffic and either serves it (static files), forwards it to your app (reverse proxy), or distributes it across many app instances (load balancer). Often the same tool plays multiple roles.

---

## 1. Nginx — Popularity: 10/10

**Purpose:**
The most widely deployed web server. Acts as static file server, reverse proxy (in front of PHP-FPM/Node/Python), load balancer, and HTTP cache. Configured via `nginx.conf`. You already use it.

**Alternatives:** (see below)

**When to learn it:** beginner — master `server`, `location`, `upstream`, `proxy_pass`, rate limiting, gzip, and SSL termination.

---

## 2. Apache HTTP Server — Popularity: 7/10

**Purpose:**
The original web server. Configured via `httpd.conf` and `.htaccess`. Still common in shared hosting and legacy PHP apps. Slightly heavier than Nginx; uses module-based architecture (`mod_rewrite`, `mod_php`).

**When to learn it:** intermediate — useful if dealing with legacy hosts.

---

## 3. HAProxy — Popularity: 8/10

**Purpose:**
High-performance L4/L7 load balancer. Often used in front of multiple app servers. Famous for reliability and observability (live stats UI). Less common as a static-file server.

**When to learn it:** intermediate — for multi-server load balancing.

---

## 4. Traefik — Popularity: 8/10

**Purpose:**
Modern reverse proxy that auto-discovers services from Docker labels, Kubernetes, Consul, etc. Built-in Let's Encrypt for free SSL. Fantastic for container-based stacks.

**When to learn it:** intermediate — easier than Nginx for dynamic environments.

---

## 5. Envoy — Popularity: 8/10

**Purpose:**
High-performance L7 proxy from Lyft. Foundational layer of Istio service mesh and many API gateways. Extremely featureful (traffic shaping, retries, circuit breaking, observability) but complex.

**When to learn it:** advanced — typically encountered through service mesh.

---

## 6. Caddy — Popularity: 7/10

**Purpose:**
Modern web server with **automatic HTTPS** via Let's Encrypt out of the box. One-line config (`Caddyfile`). Friendly for beginners; less battle-tested than Nginx at scale.

**When to learn it:** beginner — quickest way to get HTTPS on a personal project.

---

## 7. AWS Application Load Balancer (ALB) — Popularity: 9/10

**Purpose:**
AWS-managed L7 load balancer. Path/host-based routing, SSL termination, integrates with ECS/EKS/Lambda. The default in AWS.

**Alternatives:**
- **AWS NLB (Network Load Balancer)** — 8/10 — L4, ultra-low latency, TCP/UDP.
- **AWS CLB (Classic)** — 3/10 — Legacy.

**When to learn it:** intermediate — for AWS roles.

---

## 8. LiteSpeed / OpenLiteSpeed — Popularity: 4/10

**Purpose:**
Apache-compatible web server known for being faster than Nginx with WordPress (LSCache plugin). Used in some shared hosts. OpenLiteSpeed is the free version.

**When to learn it:** beginner — only if you encounter it on shared hosting.

---

## 9. Cloudflare (as load balancer / proxy) — Popularity: 8/10

**Purpose:**
Cloudflare proxies traffic globally. Acts as DDoS protection + CDN + edge SSL + load balancer. You point DNS at Cloudflare; it forwards to your origin.

**When to learn it:** beginner — almost free, almost always useful.

---

## Concepts to Master

- **Web server vs Reverse proxy vs Load balancer** — overlapping roles
- **Forward proxy vs Reverse proxy** — forward = client-side; reverse = server-side
- **Layer 4 (TCP) vs Layer 7 (HTTP)** — what level the LB operates at
- **SSL / TLS termination** — decrypt at the LB so backends don't have to
- **Sticky sessions** — pinning a user to one backend
- **Health checks** — LB pings backends; removes unhealthy ones
- **Round-robin / Least-conn / IP-hash** — load balancing algorithms
- **Keep-alive** — reusing TCP connections
- **gzip / brotli** — compression
- **Rate limiting** — throttle abusive clients
- **WebSocket support** — long-lived bidirectional connections (Nginx needs `Upgrade` headers configured)
