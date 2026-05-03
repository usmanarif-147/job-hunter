# Realtime / WebSockets

> Push data **from server to clients** instantly — chat, notifications, live dashboards, collaborative editing. Built on WebSockets (or Server-Sent Events, or long polling as fallback).

---

## 1. Laravel Reverb — Popularity: 8/10 (Laravel ecosystem)

**Purpose:**
Laravel's first-party WebSocket server (released 2024). Handles thousands of connections, integrates natively with Laravel broadcasting. Free, self-hosted. The default for new Laravel realtime apps.

**Alternatives within Laravel ecosystem:** (see Pusher, Soketi below)

**When to learn it:** beginner — modern Laravel default.

---

## 2. Pusher — Popularity: 8/10

**Purpose:**
SaaS WebSocket service. Tightly integrated with Laravel broadcasting. Generous free tier (200K messages/day, 100 concurrent). Outgrowing it gets expensive — that's when teams move to Reverb/Soketi.

**When to learn it:** beginner — easiest realtime in Laravel.

---

## 3. Soketi — Popularity: 6/10

**Purpose:**
Open-source, drop-in Pusher API replacement. Self-host for free what Pusher charges for. Written in Node/TypeScript.

**When to learn it:** intermediate — alternative to Reverb if you want Pusher protocol.

---

## 4. Socket.IO — Popularity: 9/10

**Purpose:**
Long-running JS realtime library. Handles WebSockets + automatic fallbacks (polling, etc.). Rooms, namespaces, broadcasting. Default in Node.js apps.

**When to learn it:** intermediate — for Node-based realtime.

---

## 5. Centrifugo — Popularity: 6/10

**Purpose:**
Fast self-hosted realtime server in Go. Supports WebSocket, SSE, HTTP-streaming. Used by GitLab. Polyglot (works with any backend).

**When to learn it:** advanced — for high-scale realtime.

---

## 6. Ably — Popularity: 7/10

**Purpose:**
Premium realtime SaaS. Multi-region, message ordering, history, presence. Paid; used by enterprises.

**When to learn it:** intermediate — recognise it.

---

## 7. PubNub — Popularity: 6/10

**Purpose:**
Same niche as Ably — global realtime SaaS. Old guard.

**When to learn it:** advanced — niche.

---

## 8. Liveblocks — Popularity: 6/10

**Purpose:**
Realtime SDK specifically for **collaborative apps** (cursors, presence, CRDT-backed shared state). Like Figma/Notion-style multiplayer.

**When to learn it:** advanced — niche but trending.

---

## 9. Phoenix Channels (Elixir) — Popularity: 6/10

**Purpose:**
Phoenix's built-in realtime. Famously handles 2 million concurrent connections on a single server. Best-in-class for realtime if you can use Elixir.

**When to learn it:** advanced — if doing Elixir.

---

## 10. Server-Sent Events (SSE) — Popularity: 7/10

**Purpose:**
HTTP-based one-way streaming from server to client. Simpler than WebSockets when you only need server → client (no client → server). Works through proxies that don't speak WebSocket.

**When to learn it:** intermediate — increasingly popular for AI chat streaming.

---

## 11. WebRTC — Popularity: 7/10

**Purpose:**
Peer-to-peer realtime media (audio/video/data). Used for video calls (Google Meet, Discord), screen sharing. Different category but worth knowing.

**Tools:**
- **LiveKit** — 7/10 — Open-source WebRTC infrastructure.
- **Twilio Programmable Video** — 7/10 — SaaS.
- **Agora** — 6/10 — SaaS, popular in Asia.

**When to learn it:** advanced — for video/audio apps.

---

## Realtime Patterns to Master

- **Pub/Sub** — broadcast messages to many subscribers
- **Channels / Rooms / Topics** — group subscribers
- **Presence** — "who is currently online?"
- **Private channels** — auth-required channels
- **Presence channels** — channels that also expose member list
- **Broadcasting events** — Laravel `event(new MessageSent($msg))`
- **Polling vs Long-polling vs SSE vs WebSocket** — escalating realtime techniques
- **Reconnection strategies** — exponential backoff, message replay
- **Backpressure** — slow clients shouldn't block fast ones
- **Horizontal scaling** — multiple WS servers + Redis pub/sub for cross-server fan-out
- **Sticky sessions** — pin client to same WS server
