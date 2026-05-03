# Search Engines

> Specialized databases optimized for full-text search, fuzzy matching, faceting, and ranking. SQL `LIKE '%foo%'` doesn't scale — these tools do.

---

## 1. Elasticsearch — Popularity: 9/10

**Purpose:**
The dominant open-source search engine. Distributed, REST API, JSON documents. Used for site search, log analytics (ELK stack), product catalogs. Powerful but operationally heavy.

**Alternatives:** (see below)

**When to learn it:** intermediate — interview-relevant.

---

## 2. OpenSearch — Popularity: 7/10

**Purpose:**
AWS's fork of Elasticsearch (after Elastic changed its license in 2021). API-compatible with older ES versions. Default search engine in AWS-managed search.

**When to learn it:** intermediate — if you know ES, you know OpenSearch.

---

## 3. Meilisearch — Popularity: 7/10

**Purpose:**
Modern, lightning-fast, developer-friendly search engine written in Rust. Typo tolerance out of the box, easy to deploy (single binary). Ideal for site/app search where ES is overkill.

**When to learn it:** beginner — easy win for product search.

---

## 4. Typesense — Popularity: 6/10

**Purpose:**
Same niche as Meilisearch — fast, easy, typo-tolerant search. Open source. Strong free hosted tier.

**When to learn it:** beginner — alternative to Meilisearch.

---

## 5. Algolia — Popularity: 8/10

**Purpose:**
SaaS search platform. Insanely fast, polished APIs, great UI components (InstantSearch). Expensive at scale. Used by Stripe, Shopify, Twitch.

**When to learn it:** intermediate — common in commercial products with strong search needs.

---

## 6. Apache Solr — Popularity: 5/10

**Purpose:**
Older Apache project (predates ES). Strong full-text capabilities, mature. Less common in new projects.

**When to learn it:** advanced — only for legacy projects.

---

## 7. PostgreSQL Full-Text Search — Popularity: 7/10

**Purpose:**
Postgres has built-in full-text search via `tsvector` and `tsquery`. Sufficient for many small-to-mid apps without adding a separate search service.

**When to learn it:** beginner — the simplest "search" choice.

---

## 8. Laravel Scout — Popularity: 7/10 (within Laravel ecosystem)

**Purpose:**
Laravel's built-in driver-based search abstraction. Works with Algolia, Meilisearch, Elasticsearch (via package), or even a database driver. Use this in Laravel apps.

**When to learn it:** beginner — if you're staying in Laravel.

---

## 9. Vector / Semantic Search (newer category)

**Purpose:**
Search by **meaning**, not keywords. Embeds documents into vectors via an LLM, then finds nearest neighbors. Used in AI/RAG apps.

**Tools:**
- **Pinecone** — 8/10 — Hosted vector DB.
- **Weaviate** — 6/10 — Open-source vector DB with hybrid search.
- **Qdrant** — 7/10 — Rust-based vector DB.
- **Milvus** — 6/10 — Open-source, large scale.
- **pgvector** — 8/10 — Postgres extension; everyone uses this for cheap vector search.

**When to learn it:** advanced — relevant if working on AI features.

---

## Concepts to Master

- **Inverted index** — the data structure that makes search fast
- **Tokenization / Analyzers / Stemming** — how text is split and normalized
- **Faceting** — sidebar filters (price, category, brand)
- **Boosting** — rank some fields higher (title vs body)
- **Synonyms** — "phone" ↔ "mobile" ↔ "cell"
- **Typo tolerance / Fuzzy matching** — "lravael" → "laravel"
- **Relevance scoring** — TF-IDF, BM25
- **Index vs Document vs Field** — search engine vocabulary
- **Re-indexing** — bulk loading data; how to do it without downtime
