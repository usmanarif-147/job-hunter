# Message Brokers & Event Streaming

> Software that lets services communicate **asynchronously**. One service publishes a message; one or more others consume it. Decouples producers from consumers — essential for microservices.

---

## 1. Apache Kafka — Popularity: 10/10

**Purpose:**
Distributed event-streaming platform. Stores messages in append-only logs (topics) split into partitions. Designed for massive throughput (millions of msgs/sec) and replay. Industry standard for event-driven architectures, analytics pipelines, and inter-service communication at scale.

**Alternatives:** (see below — Kafka is in a class of its own for streaming)

**When to learn it:** intermediate — you already touched it; deepen here.

---

## 2. RabbitMQ — Popularity: 9/10

**Purpose:**
Traditional message broker (AMQP protocol). Rich routing (exchanges, queues, bindings), reliable delivery, dead-letter queues. Better than Kafka for traditional task queues and complex routing; worse for high-throughput streaming.

**Alternatives:**
- See full list at end of file.

**When to learn it:** intermediate — common in Laravel/Symfony shops via Symfony Messenger.

---

## 3. Redis Streams / Pub-Sub / Queues — Popularity: 9/10

**Purpose:**
Redis can act as a lightweight message broker. **Pub/Sub** (fire-and-forget), **Streams** (Kafka-like log), and **Lists** (used by Laravel queues). Fastest to set up; great for small-to-mid-scale projects.

**When to learn it:** beginner — Laravel + Redis queues is the most common combo.

---

## 4. AWS SQS — Popularity: 9/10

**Purpose:**
AWS-managed queue service. Two flavors: **Standard** (at-least-once, unordered) and **FIFO** (ordered, exactly-once). Zero ops, pay-per-message. The default in serverless AWS architectures.

**Alternatives:**
- **AWS SNS** — 8/10 — Pub/sub topic service; often paired with SQS for fan-out.
- **AWS EventBridge** — 7/10 — Event bus for AWS services; more featureful than SNS.

**When to learn it:** intermediate — needed for AWS-heavy roles.

---

## 5. NATS — Popularity: 7/10

**Purpose:**
Lightweight, blazing-fast messaging system written in Go. Simpler than Kafka or RabbitMQ. NATS JetStream adds streaming/persistence. Loved in Go/Rust microservice ecosystems.

**Alternatives:**
- See full list below.

**When to learn it:** intermediate — modern microservice stacks often pick NATS.

---

## 6. Apache Pulsar — Popularity: 6/10

**Purpose:**
Kafka competitor. Multi-tenant, geo-replication, separates compute from storage. Used by Yahoo, Tencent. Steeper learning curve.

**When to learn it:** advanced — only at large scale.

---

## 7. Google Pub/Sub — Popularity: 7/10

**Purpose:**
GCP-managed messaging. Globally distributed, at-least-once delivery. The GCP equivalent of SNS+SQS combined.

**When to learn it:** intermediate — for GCP roles.

---

## 8. ActiveMQ — Popularity: 4/10

**Purpose:**
Older Java/JMS broker. Still widespread in enterprise Java. ActiveMQ Artemis is the modern rewrite.

**When to learn it:** advanced — only in legacy Java enterprise.

---

## 9. ZeroMQ — Popularity: 4/10

**Purpose:**
Library (not a broker!) for in-process / inter-process messaging. Embedded inside applications. Different category than the rest.

**When to learn it:** advanced — niche.

---

## Choosing Between Them — Quick Decision Guide

- **Need persistent event log + replay + streaming?** → Kafka
- **Need traditional task queue with rich routing?** → RabbitMQ
- **Already using Redis + small/mid scale?** → Redis Streams or Lists
- **All-in on AWS?** → SQS (+ SNS/EventBridge)
- **All-in on GCP?** → Pub/Sub
- **Microservices, want lightweight + fast?** → NATS
- **At Netflix-scale?** → Kafka or Pulsar

---

## Concepts to Master Alongside the Tools

- **Producer / Consumer** — who publishes, who reads
- **Topic / Queue / Exchange** — the channel
- **Partitions** — how Kafka parallelizes
- **Consumer Groups** — load-balancing across consumers
- **At-most-once / At-least-once / Exactly-once** delivery semantics
- **Dead-letter queues** — where failed messages go
- **Backpressure** — what happens when consumers are slow
- **Event-driven architecture vs request-response**
