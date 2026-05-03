# Service Mesh

> Infrastructure layer that handles **service-to-service communication** in microservice systems. Adds mTLS, retries, timeouts, traffic shifting, observability — without changing app code. Sits as a "sidecar" proxy next to each service.

---

## 1. Istio — Popularity: 9/10

**Purpose:**
The most popular service mesh. Built on Envoy. Adds mTLS encryption, traffic management (canary, mirroring), policies (AuthorizationPolicy), and observability between K8s services. Powerful but complex to operate.

**Alternatives:** (see below)

**When to learn it:** advanced — only after solid K8s fundamentals.

---

## 2. Linkerd — Popularity: 7/10

**Purpose:**
Lightweight service mesh. Written in Rust. Simpler than Istio, faster to install, lower resource footprint. CNCF graduated. Good first mesh.

**When to learn it:** advanced — easier on-ramp than Istio.

---

## 3. Consul Connect (HashiCorp) — Popularity: 5/10

**Purpose:**
Service mesh built into HashiCorp Consul. Combines service discovery + mesh + key/value store. Works on K8s and VMs (unique selling point).

**When to learn it:** advanced — only in HashiCorp shops.

---

## 4. AWS App Mesh — Popularity: 4/10

**Purpose:**
AWS-managed service mesh. Built on Envoy. Less popular than Istio even on AWS; AWS itself often pushes alternatives now.

**When to learn it:** advanced — niche.

---

## 5. Cilium / Cilium Service Mesh — Popularity: 7/10

**Purpose:**
eBPF-based networking and service mesh. Replaces Envoy sidecars with kernel-level eBPF programs — more efficient. Cilium is the default CNI in many modern K8s clusters.

**When to learn it:** advanced — cutting edge.

---

## 6. Kuma — Popularity: 4/10

**Purpose:**
Service mesh from the creators of Kong. Multi-zone, multi-cloud, K8s + VM support.

**When to learn it:** advanced — niche.

---

## What Service Mesh Actually Solves

Without a mesh, every microservice has to implement on its own:

- mTLS encryption between services
- Retries / timeouts / circuit breakers
- Load balancing
- Authorization (which services can talk to which)
- Distributed tracing
- Detailed metrics

A service mesh moves all this **out of your app code** and into a sidecar proxy (Envoy/Linkerd-proxy). Your service just sends plain HTTP to `localhost`; the proxy handles the rest.

---

## When You Actually Need One

You **do** need a service mesh when:
- 10+ microservices talking to each other
- Need mTLS between services for compliance
- Need fine-grained traffic policies (canary, shifting)
- Need consistent observability across many services

You **don't** need one if:
- You have 2–3 services
- A monolith + a few queue workers
- Simple request-response APIs without complex topology

Service meshes are powerful but expensive to operate. **Don't adopt prematurely.**

---

## Concepts to Master

- **Sidecar pattern** — proxy runs as a co-located container alongside your app
- **Data plane vs Control plane** — sidecars (data) vs central config server (control)
- **mTLS** — mutual TLS; both sides authenticate each other
- **Ingress vs Egress traffic** — into vs out of the mesh
- **Traffic shifting** — gradual canary deploys (10% → 50% → 100%)
- **Traffic mirroring** — duplicate prod traffic to a test version
- **Circuit breaker** — stop calling a failing service to let it recover
- **Retries with budgets** — limit total retry load
- **East-west traffic** (service-to-service) vs **North-south** (client-to-cluster)
