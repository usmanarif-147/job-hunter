# Object Storage

> Store unlimited unstructured files (images, videos, PDFs, backups, logs) at low cost. Accessed by HTTP. Different from block storage (EBS) and file storage (NFS).

---

## 1. Amazon S3 — Popularity: 10/10

**Purpose:**
The original and dominant object storage. Buckets contain objects accessed by key. Many storage classes (Standard, Infrequent Access, Glacier for archive). Used as the API standard — almost every other provider is "S3-compatible."

**Alternatives:** (see below)

**When to learn it:** beginner — foundational; integrate with Laravel `Storage::disk('s3')`.

---

## 2. MinIO — Popularity: 8/10

**Purpose:**
Self-hosted S3-compatible object storage. Single Go binary or Docker container. Run on your own VPS/K8s cluster. Use locally to develop without paying AWS. Production-grade at scale too.

**When to learn it:** intermediate — great for self-hosted setups.

---

## 3. Cloudflare R2 — Popularity: 8/10

**Purpose:**
S3-compatible storage from Cloudflare with **zero egress fees** (no charge to download data). Pay only for storage and operations. Massive savings vs S3 for read-heavy workloads. Free tier: 10GB.

**When to learn it:** beginner — best value object storage today.

---

## 4. Google Cloud Storage (GCS) — Popularity: 8/10

**Purpose:**
GCP's object storage. S3-compatible API. Tight integration with BigQuery and Vertex AI.

**When to learn it:** intermediate — for GCP roles.

---

## 5. Azure Blob Storage — Popularity: 8/10

**Purpose:**
Azure's object storage. Hot, cool, archive tiers. Tightly integrated with Azure services.

**When to learn it:** intermediate — for Azure roles.

---

## 6. Backblaze B2 — Popularity: 7/10

**Purpose:**
Cheap, S3-compatible cloud storage. ~⅕ the price of S3. Free egress when paired with Cloudflare. Indie/SMB favorite.

**When to learn it:** beginner — cost-effective alternative.

---

## 7. Wasabi — Popularity: 5/10

**Purpose:**
Same niche as Backblaze. Cheap S3-compatible. No egress fees but minimum-storage-time policies.

**When to learn it:** intermediate — alternative.

---

## 8. DigitalOcean Spaces — Popularity: 6/10

**Purpose:**
DigitalOcean's S3-compatible storage. Bundled CDN. Simple pricing.

**When to learn it:** beginner — if you're already on DO.

---

## 9. Hetzner Storage Box / Object Storage — Popularity: 5/10

**Purpose:**
Hetzner's storage offerings — Storage Box (block-style file storage over SMB/SFTP) and Object Storage (S3-compatible, newer). Very cheap.

**When to learn it:** beginner — for Hetzner-based deployments.

---

## Concepts to Master

- **Bucket / Object / Key / Prefix** — S3 vocabulary
- **Public vs Private buckets** — by default keep them private
- **Pre-signed URLs** — temporary URLs for secure direct upload/download
- **CORS** — required for browser-based uploads
- **Multipart upload** — for files > 5GB; resumable
- **Storage classes / Lifecycle policies** — auto-tier or delete old data
- **Versioning** — keep previous object versions
- **Server-side encryption** — SSE-S3, SSE-KMS, SSE-C
- **Bucket policies** — JSON access rules
- **Access Control Lists (ACLs)** — older permission system; AWS now recommends bucket policies
- **Egress fees** — moving data OUT of the cloud is the expensive part (R2/B2 avoid this)
- **CDN integration** — put CloudFront/Cloudflare in front for caching
- **S3-compatible** — magic phrase that means "same API, different provider"
- **Direct uploads from browser** — saves your server bandwidth (use pre-signed POST/PUT)
- **Image transforms** — services like ImgIX, Cloudinary, Cloudflare Images on top of object storage
