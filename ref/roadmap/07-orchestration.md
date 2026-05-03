# Container Orchestration

> Tools that run containers across many machines, handling scheduling, scaling, self-healing, networking, and rolling deployments. Required when one machine isn't enough or you need zero-downtime deploys.

---

## 1. Kubernetes (K8s) — Popularity: 10/10

**Purpose:**
The industry standard orchestrator. Schedules containers across a cluster of nodes, auto-restarts failed pods, scales horizontally, manages networking, secrets, configs, storage. Built by Google, donated to CNCF. Steep learning curve but unavoidable for modern DevOps.

**Alternatives:** (managed flavors below; conceptual alternatives further below)

**When to learn it:** intermediate-to-advanced — high career ROI; start with **minikube** or **kind** locally.

---

## 2. Amazon EKS — Popularity: 9/10

**Purpose:**
AWS-managed Kubernetes. AWS runs the control plane; you manage worker nodes (or use Fargate for serverless nodes). The AWS-native way to run K8s.

**Alternatives:**
- **GKE** (Google) — 9/10 — Many consider it the best managed K8s; Google invented K8s.
- **AKS** (Azure) — 8/10 — Microsoft's managed K8s.
- **DigitalOcean Kubernetes** — 7/10 — Cheap, simple.
- **Linode LKE** — 6/10 — Same idea, smaller cloud.

**When to learn it:** intermediate — once you know K8s basics, pick one managed flavor.

---

## 3. Amazon ECS — Popularity: 8/10

**Purpose:**
AWS-native container orchestrator (not K8s). Simpler than EKS, tightly integrated with AWS. Two launch types: **EC2** (you manage VMs) and **Fargate** (serverless). Good first step into AWS containers without K8s complexity.

**When to learn it:** intermediate — fast win for AWS roles that don't need K8s.

---

## 4. Docker Swarm — Popularity: 5/10

**Purpose:**
Docker's built-in orchestrator. Much simpler than K8s; uses the same `docker compose` syntax with `docker stack deploy`. Lost the war to K8s but still used in small teams.

**When to learn it:** intermediate — quick to learn, limited future-proofing.

---

## 5. HashiCorp Nomad — Popularity: 6/10

**Purpose:**
Lightweight orchestrator from HashiCorp. Simpler than K8s, can run containers AND non-containerized workloads (binaries, Java, etc.). Pairs well with Consul + Vault.

**When to learn it:** advanced — niche but excellent.

---

## 6. OpenShift (Red Hat) — Popularity: 7/10

**Purpose:**
Red Hat's enterprise Kubernetes distribution. K8s + opinionated tooling, security, developer console, CI/CD pipelines (Tekton). Common in regulated enterprises (banks, telcos).

**When to learn it:** advanced — only for enterprise/Red Hat shops.

---

## 7. Rancher — Popularity: 6/10

**Purpose:**
Kubernetes management platform. Not an alternative to K8s — it manages **multiple K8s clusters**. UI-driven, multi-cloud.

**When to learn it:** advanced — useful for multi-cluster ops.

---

## Kubernetes Concepts to Learn (Roughly in Order)

1. **Pod** — smallest deployable unit (one or more containers).
2. **Deployment** — manages a set of Pods with rolling updates.
3. **Service** — stable network endpoint for Pods.
4. **Ingress** — routes external HTTP traffic into Services.
5. **ConfigMap / Secret** — config and credentials.
6. **PersistentVolume / PersistentVolumeClaim** — storage.
7. **Namespace** — logical isolation.
8. **StatefulSet** — for stateful apps (databases) with stable identity.
9. **DaemonSet** — runs one Pod per node (e.g., log shipper).
10. **Job / CronJob** — one-off and scheduled tasks.
11. **HorizontalPodAutoscaler (HPA)** — auto-scale Pods.
12. **RBAC** — role-based access control.
13. **Helm** — package manager for K8s (chart = K8s YAML template).
14. **kubectl** — the CLI you'll live in.

## Local Tools to Practice K8s for Free

- **minikube** — runs a single-node cluster locally.
- **kind** — Kubernetes in Docker; faster than minikube.
- **k3d** — runs k3s (lightweight K8s) in Docker.
- **k3s** — lightweight production K8s; great on small VPS.
- **Docker Desktop** — has a "Enable Kubernetes" toggle.
