# Cloud Providers

> Companies that rent you compute, storage, networking, and managed services on demand. Where your modern apps actually run.

---

## 1. Amazon Web Services (AWS) — Popularity: 10/10

**Purpose:**
The market leader. Hundreds of services across compute (EC2, Lambda, ECS, EKS), storage (S3, EBS), databases (RDS, DynamoDB, Aurora), networking (VPC, Route 53, CloudFront), and more. Free tier covers 12 months for many services. Mandatory for most modern backend roles.

**Core services to learn first:**
- **EC2** — virtual servers
- **S3** — object storage
- **RDS** — managed relational DB
- **VPC** — networking
- **IAM** — permissions
- **Route 53** — DNS
- **CloudFront** — CDN
- **Lambda** — serverless functions
- **SQS / SNS** — messaging
- **CloudWatch** — logs/metrics

**When to learn it:** intermediate — high career ROI; free-tier-friendly.

---

## 2. Google Cloud Platform (GCP) — Popularity: 8/10

**Purpose:**
Google's cloud. Strong in data (BigQuery), ML (Vertex AI), and Kubernetes (GKE — Google invented K8s). Cleaner UI/UX than AWS. Smaller market share but strong technical reputation.

**Core services to know:**
- **Compute Engine** (≈ EC2)
- **GKE** (managed Kubernetes)
- **Cloud Storage** (≈ S3)
- **Cloud SQL** (≈ RDS)
- **Pub/Sub** (≈ SNS+SQS)
- **BigQuery** (data warehouse)
- **Cloud Run** (serverless containers)

**When to learn it:** intermediate — second cloud after AWS.

---

## 3. Microsoft Azure — Popularity: 8/10

**Purpose:**
Microsoft's cloud. Dominant in enterprise (especially companies using Office 365, Active Directory, .NET). Strong hybrid-cloud story.

**Core services to know:**
- **Virtual Machines**
- **AKS** (managed Kubernetes)
- **Azure SQL** (managed SQL Server)
- **Blob Storage**
- **Azure Functions**
- **Service Bus**

**When to learn it:** intermediate — for enterprise/.NET roles.

---

## 4. DigitalOcean — Popularity: 7/10

**Purpose:**
Developer-friendly cloud. Simple UI, predictable pricing ("Droplets" = VMs from $4/month). Managed databases, K8s, App Platform. Excellent for indie devs and small teams.

**When to learn it:** beginner — easiest cloud to start with.

---

## 5. Hetzner — Popularity: 7/10

**Purpose:**
German cloud, **insanely cheap** dedicated servers and VPS. Great price/performance — used by many cost-conscious teams. EU and US locations. No fancy managed services, just solid VMs and storage.

**When to learn it:** beginner — best value-for-money for personal projects.

---

## 6. Linode (Akamai Cloud) — Popularity: 5/10

**Purpose:**
Long-running developer-friendly VPS provider, now owned by Akamai. Similar to DigitalOcean. Good for small VPS workloads.

**When to learn it:** beginner — alternative to DigitalOcean.

---

## 7. Vultr — Popularity: 5/10

**Purpose:**
Another DigitalOcean-style VPS provider with competitive pricing and many regions worldwide.

**When to learn it:** beginner — alternative to DigitalOcean / Linode.

---

## 8. Cloudflare (compute side) — Popularity: 8/10

**Purpose:**
Originally a CDN, now offers **Cloudflare Workers** (edge serverless), **R2** (S3-compatible storage with no egress fees), **D1** (SQLite at the edge), **Pages** (static hosting). Fast-growing modern alternative for edge-first apps.

**When to learn it:** intermediate — modern stack favorite.

---

## 9. Oracle Cloud (OCI) — Popularity: 4/10

**Purpose:**
Oracle's cloud. Famous for its **Always Free** tier (4 ARM cores, 24GB RAM forever — no joke). Less polished UX, niche outside Oracle-using enterprises.

**When to learn it:** beginner — only as a free-tier hack for personal projects.

---

## 10. Alibaba Cloud / Tencent Cloud — Popularity: 5/10

**Purpose:**
Dominant in China/Asia. Important if your market is Asian.

**When to learn it:** advanced — region-specific.

---

## Concepts to Master

- **Region / Availability Zone (AZ)** — datacenters and their fault isolation
- **VPC / Subnet / Security Group / Route Table** — virtual networking
- **IAM** — users, roles, policies, permissions
- **Public vs Private subnet** — internet-facing vs internal
- **NAT Gateway / Internet Gateway** — how subnets reach the internet
- **Auto-scaling group** — automatic horizontal scaling
- **Load balancer** — distributes traffic across instances
- **Managed vs Self-hosted** — RDS vs MySQL on EC2
- **Spot / Preemptible instances** — cheap, can be terminated anytime
- **Reserved / Savings Plans** — pre-paying for big discounts
- **Free tier** — every cloud has one; learn its limits
