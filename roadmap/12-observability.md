# Observability — Logs, Metrics, Traces, Errors

> "How do we know what's happening in production?" The **three pillars of observability** are **Logs** (what happened), **Metrics** (how much / how often), and **Traces** (one request's journey across services). Plus error tracking as a layer on top.

---

## 1. Prometheus — Popularity: 10/10

**Purpose:**
Industry-standard metrics collector. Pull-based (scrapes endpoints exposing `/metrics`). Time-series database. Powerful query language (PromQL). Almost mandatory in K8s.

**Alternatives:**
- **InfluxDB** — 6/10 — Push-based time-series DB; simpler for IoT.
- **VictoriaMetrics** — 6/10 — Prometheus-compatible, faster.

**When to learn it:** intermediate — pair with Grafana.

---

## 2. Grafana — Popularity: 10/10

**Purpose:**
The visualization layer. Builds dashboards on top of Prometheus, Loki, Tempo, InfluxDB, and many other data sources. The standard "ops dashboard" tool everywhere.

**When to learn it:** intermediate — pair with Prometheus.

---

## 3. ELK Stack (Elasticsearch + Logstash + Kibana) — Popularity: 9/10

**Purpose:**
The classic centralized logging stack. **Logstash** collects logs, **Elasticsearch** stores and indexes them, **Kibana** queries and visualizes. Many teams replace Logstash with **Filebeat** or **Fluentd** ("EFK" stack).

**Alternatives:**
- **OpenSearch** — 7/10 — AWS fork after Elasticsearch license change.

**When to learn it:** intermediate — heavyweight but ubiquitous.

---

## 4. Grafana Loki — Popularity: 8/10

**Purpose:**
Log aggregation system inspired by Prometheus. Indexes only labels (not full text), so it's cheap to run. Pairs natively with Grafana. Modern alternative to ELK for many teams.

**When to learn it:** intermediate — easier to operate than ELK.

---

## 5. OpenTelemetry (OTel) — Popularity: 9/10

**Purpose:**
Vendor-neutral standard (and SDKs) for **traces, metrics, and logs**. Lets you instrument your app once and export to any backend (Jaeger, Datadog, New Relic, ...). Backed by CNCF. The future of observability instrumentation.

**When to learn it:** intermediate — start instrumenting Laravel/Node/Go services with OTel.

---

## 6. Jaeger — Popularity: 8/10

**Purpose:**
Open-source distributed tracing system (originated at Uber). Stores and queries traces; UI shows request flow across services with latency per span. CNCF graduated.

**Alternatives:**
- **Zipkin** — 6/10 — Older tracing system; from Twitter.
- **Grafana Tempo** — 7/10 — Trace backend that pairs with Grafana.

**When to learn it:** intermediate — pair with OpenTelemetry.

---

## 7. Sentry — Popularity: 9/10

**Purpose:**
Error and performance tracking. Captures exceptions with stack traces, breadcrumbs, environment context. Integrates with virtually every framework (including Laravel). Generous free tier.

**Alternatives:**
- **Bugsnag** — 6/10 — Similar product, smaller.
- **Rollbar** — 5/10 — Similar, fading.
- **Honeybadger** — 4/10 — Smaller alternative.
- **GlitchTip** — 4/10 — Open-source, Sentry API-compatible (self-host).

**When to learn it:** beginner — easiest observability win.

---

## 8. Datadog — Popularity: 9/10

**Purpose:**
Premium SaaS that bundles **logs + metrics + traces + APM + RUM + security**. Beautiful UI, expensive. Industry standard at well-funded companies.

**Alternatives:**
- **New Relic** — 8/10 — Same category, also premium.
- **Dynatrace** — 7/10 — AI-driven, enterprise.
- **AppDynamics** — 5/10 — Cisco's APM, fading.

**When to learn it:** intermediate — interview-relevant; you'll likely encounter it.

---

## 9. Splunk — Popularity: 8/10

**Purpose:**
Enterprise log/data analytics platform. Extremely powerful query language (SPL). Common in regulated industries (finance, security). Very expensive.

**When to learn it:** advanced — only in large enterprise/security roles.

---

## 10. AWS CloudWatch — Popularity: 9/10

**Purpose:**
AWS-native logs + metrics + alarms. Default for anything running on AWS. Less polished than Datadog but tightly integrated with all AWS services.

**When to learn it:** intermediate — needed for AWS roles.

---

## 11. Honeycomb — Popularity: 6/10

**Purpose:**
Modern observability platform focused on high-cardinality event data. Strong on debugging complex distributed systems. Loved by engineering-heavy teams.

**When to learn it:** advanced — niche but innovative.

---

## 12. Better Stack (formerly Logtail) — Popularity: 5/10

**Purpose:**
Modern logs + uptime monitoring SaaS. Friendly UI, generous free tier, good for small teams.

**Alternatives:**
- **Papertrail** — 4/10 — Simple SaaS logging.
- **Logz.io** — 4/10 — Managed ELK.

**When to learn it:** beginner — quick logging for personal projects.

---

## 13. Uptime Monitoring

| Tool | Popularity | Notes |
|---|---|---|
| **UptimeRobot** | 8/10 | Free tier, simple |
| **Better Uptime / Better Stack** | 6/10 | Modern, status pages |
| **Pingdom** | 6/10 | Veteran, premium |
| **StatusCake** | 4/10 | Solid alternative |

---

## The Three Pillars Quick Reference

| Pillar | Question it answers | Tools |
|---|---|---|
| **Logs** | "What happened at 03:14?" | ELK, Loki, CloudWatch, Splunk |
| **Metrics** | "How many requests/sec? CPU%?" | Prometheus + Grafana, Datadog, CloudWatch |
| **Traces** | "Why was THIS request slow?" | Jaeger, Tempo, OpenTelemetry, Datadog APM |
| **Errors** | "Did that bug ship to prod?" | Sentry, Bugsnag, Rollbar |
