# Databases

> Where your application's persistent data lives. Most modern systems use a relational database as primary storage and may add NoSQL stores for specific workloads.

---

## 1. PostgreSQL — Popularity: 10/10

**Purpose:**
The modern default relational database. Open source, ACID-compliant, supports JSON, full-text search, geospatial, advanced indexing, and replication. Preferred by most new projects, including Laravel apps where the team has a choice.

**Alternatives:**
- **MySQL** — 9/10 — Slightly easier ops, huge ecosystem; still the most common in PHP/Laravel shops.
- **MariaDB** — 6/10 — Open-source MySQL fork; safer politically (not Oracle-owned).
- **SQLite** — 8/10 — Single-file embedded DB; great for testing and small apps.
- **MS SQL Server** — 6/10 — Common in .NET/enterprise.
- **Oracle DB** — 5/10 — Legacy enterprise.

**When to learn it:** beginner — Job 4 lists Postgres as primary; very high ROI to migrate from MySQL knowledge.

---

## 2. MySQL — Popularity: 9/10

**Purpose:**
The classic open-source relational DB. Easy to operate, ubiquitous in PHP/Laravel apps, well-documented. Strong default for monolithic apps and shared hosting.

**Alternatives:** see PostgreSQL section.

**When to learn it:** beginner — you already know this.

---

## 3. Redis (as a database / KV store) — Popularity: 10/10

**Purpose:**
In-memory key-value store. Used as cache (next file) but also as a primary store for sessions, queues, leaderboards, rate limiting, and pub/sub. Persists to disk optionally.

**Alternatives:**
- **KeyDB** — 4/10 — Multi-threaded Redis fork.
- **Dragonfly** — 5/10 — Modern Redis-compatible alternative; faster.

**When to learn it:** beginner — must-have.

---

## 4. MongoDB — Popularity: 8/10

**Purpose:**
Document database (stores JSON-like BSON). Schema-flexible. Good for content with varying shapes, fast prototyping, and apps where you don't want to write migrations. Loses popularity vs Postgres + JSONB but still huge.

**Alternatives:**
- **CouchDB** — 3/10 — Older document DB, niche.
- **Firestore** (Google) — 7/10 — Managed document DB, popular with mobile.
- **DynamoDB** — 8/10 — AWS managed, key-value/document.

**When to learn it:** intermediate — common in Node.js shops.

---

## 5. DynamoDB (AWS) — Popularity: 8/10

**Purpose:**
AWS-managed NoSQL key-value/document store. Built for massive scale, single-digit millisecond latency, pay-per-request. Used heavily in serverless architectures (Lambda + API Gateway + DynamoDB).

**Alternatives:**
- **AWS Aurora** — 8/10 — Managed MySQL/Postgres with auto-scaling (relational alternative).
- **Cassandra** (next entry).

**When to learn it:** intermediate — needed if going AWS-native.

---

## 6. Cassandra — Popularity: 6/10

**Purpose:**
Wide-column distributed NoSQL DB designed for massive write throughput across many nodes. Used by Netflix, Discord, Apple. Hard to operate; usually picked for specific scale needs.

**Alternatives:**
- **ScyllaDB** — 5/10 — C++ rewrite of Cassandra; faster.
- **HBase** — 3/10 — Older, Hadoop-based.

**When to learn it:** advanced — only at scale.

---

## 7. CockroachDB — Popularity: 6/10

**Purpose:**
Distributed SQL database (PostgreSQL-compatible) that scales horizontally and survives regional outages. "Postgres that's also distributed."

**Alternatives:**
- **TiDB** — 5/10 — MySQL-compatible distributed SQL.
- **Google Spanner** — 5/10 — Google Cloud's globally-distributed SQL.
- **Vitess** — 5/10 — Sharding layer on top of MySQL (used by YouTube, Slack).

**When to learn it:** advanced — only for very large globally-distributed systems.

---

## 8. ClickHouse — Popularity: 7/10

**Purpose:**
Columnar OLAP database for analytics. Insanely fast aggregations over billions of rows. Used for product analytics, observability backends, BI.

**Alternatives:**
- **DuckDB** — 6/10 — Embedded analytics DB; SQLite for analytics.
- **TimescaleDB** — 5/10 — Postgres extension for time-series.
- **InfluxDB** — 5/10 — Time-series DB for metrics.
- **Snowflake / BigQuery / Redshift** — 8/10 — Cloud data warehouses (different category but related).

**When to learn it:** advanced — for data-heavy roles.

---

## 9. Neo4j (Graph DB) — Popularity: 5/10

**Purpose:**
Graph database. Stores nodes and relationships. Used for social graphs, fraud detection, recommendation engines.

**Alternatives:**
- **ArangoDB** — 3/10 — Multi-model.
- **Amazon Neptune** — 4/10 — AWS managed graph.

**When to learn it:** advanced — niche.
