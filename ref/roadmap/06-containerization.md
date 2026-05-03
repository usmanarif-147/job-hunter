# Containerization

> Package an application + its dependencies + its runtime into a portable, isolated unit. Runs the same on a developer's laptop, CI server, and production cluster.

---

## 1. Docker — Popularity: 10/10

**Purpose:**
The de facto containerization platform. Provides a daemon, CLI, image format, and registry. `docker build` creates images, `docker run` starts containers. Almost every modern dev workflow uses Docker.

**Alternatives:** (see below)

**When to learn it:** beginner — you're already learning this.

---

## 2. Docker Compose — Popularity: 10/10

**Purpose:**
Tool to define and run multi-container apps via `docker-compose.yml`. Used for local dev environments and small production setups.

**When to learn it:** beginner — you already use it.

---

## 3. Podman — Popularity: 7/10

**Purpose:**
Daemonless, rootless Docker alternative from Red Hat. CLI is drop-in compatible with Docker (`alias docker=podman`). More secure (no root daemon). Default in RHEL/Fedora.

**When to learn it:** intermediate — useful for security-conscious shops.

---

## 4. containerd — Popularity: 8/10

**Purpose:**
Low-level container runtime. Docker uses it internally; Kubernetes uses it directly (since dockershim removal). You rarely interact with it as an app dev — it's the engine under the hood.

**When to learn it:** advanced — only when debugging Kubernetes nodes.

---

## 5. BuildKit — Popularity: 8/10

**Purpose:**
Modern image builder backend (replaces the old Docker builder). Parallel builds, better caching, secrets handling, multi-platform builds. Default in modern Docker Desktop.

**When to learn it:** intermediate — learn its features (`--mount=type=cache`, `--mount=type=secret`) for faster, cleaner builds.

---

## 6. LXC / LXD — Popularity: 4/10

**Purpose:**
"System containers" (Linux Containers) — closer to lightweight VMs than app containers. Run a full Linux userspace (init, syslog, multiple processes). Niche.

**When to learn it:** advanced — only for specific OS-level use cases.

---

## 7. CRI-O — Popularity: 4/10

**Purpose:**
Kubernetes-only container runtime (alternative to containerd). Smaller, K8s-focused. Used by OpenShift.

**When to learn it:** advanced — niche.

---

## Concepts You Must Know

- **Image vs Container** — image is the blueprint, container is a running instance.
- **Layers** — every Dockerfile instruction creates a layer; layers are cached.
- **Multi-stage builds** — build artifacts in one stage, copy results into a slim final image.
- **Volumes** — persistent storage outside the container's writable layer.
- **Bind mounts vs Named volumes** — bind = host path; named = Docker-managed.
- **Networks** — Docker creates virtual networks; containers in the same network reach each other by name.
- **Registries** — Docker Hub, GHCR, ECR, GCR — where images are stored/pulled.
- **`.dockerignore`** — like `.gitignore` but for `docker build` context.
- **Distroless / Alpine images** — minimize image size for faster pulls and smaller attack surface.
