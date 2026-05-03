# Payment Gateways

> Services that let your app charge customers — credit cards, wallets, subscriptions, marketplaces. Compliance (PCI DSS) is offloaded to the provider via tokenization.

---

## 1. Stripe — Popularity: 10/10

**Purpose:**
The developer-favorite payments platform. Best-in-class APIs, docs, dashboard, SDKs. Supports cards, wallets (Apple/Google Pay), bank debits, BNPL, subscriptions (Stripe Billing), marketplaces (Stripe Connect), invoicing, tax (Stripe Tax). Coverage limited in some countries (e.g., Pakistan).

**Alternatives:** (see below)

**When to learn it:** beginner — universally useful for SaaS jobs.

---

## 2. PayPal / Braintree — Popularity: 8/10

**Purpose:**
PayPal is the consumer-facing brand; **Braintree** (PayPal-owned) is the developer-friendly API. Massive global reach. Cleanly handles PayPal balances + cards.

**When to learn it:** intermediate — common in e-commerce.

---

## 3. Paddle — Popularity: 7/10

**Purpose:**
**Merchant of Record** payment platform — Paddle handles tax, invoicing, fraud, chargebacks for you. Common in indie SaaS / digital products / international sales. Higher fees than Stripe but less compliance work.

**When to learn it:** intermediate — increasingly popular.

---

## 4. LemonSqueezy — Popularity: 6/10

**Purpose:**
Same merchant-of-record idea as Paddle. Great DX, beautiful checkout. Acquired by Stripe in 2024 — still operating. Indie SaaS favorite.

**When to learn it:** intermediate — modern alternative to Paddle.

---

## 5. Razorpay — Popularity: 7/10 (in India / South Asia)

**Purpose:**
Indian payments giant. Strong UPI, cards, wallets, EMI. Default for Indian businesses.

**When to learn it:** intermediate — relevant for India-focused projects.

---

## 6. JazzCash / Easypaisa / Stripe-via-payfast / 2C2P / Safepay — Popularity: 7/10 (in Pakistan)

**Purpose:**
Pakistani payment gateways. Local cards, mobile wallets (JazzCash, Easypaisa), bank transfers. Stripe doesn't directly support PK, so most local Laravel projects integrate one of these.

**When to learn it:** intermediate — relevant for Pakistani projects.

---

## 7. Adyen — Popularity: 7/10

**Purpose:**
Enterprise payments platform. Used by Uber, Spotify, eBay. Excellent for global multi-currency operations. Less DX-friendly than Stripe.

**When to learn it:** advanced — for large enterprise.

---

## 8. Square — Popularity: 6/10

**Purpose:**
Strong in in-person + small business (POS). Has online APIs too. Mostly US/Canada/UK/Australia/Japan.

**When to learn it:** intermediate — for retail-adjacent projects.

---

## 9. Crypto Payment Processors — Popularity: 4/10

| Tool | Notes |
|---|---|
| **Coinbase Commerce** | Accept BTC/ETH/USDC; simple |
| **BitPay** | Veteran crypto processor |
| **NOWPayments** | Wide coin support |

**When to learn it:** advanced — niche.

---

## Laravel Packages for Payments

| Package | Purpose |
|---|---|
| **Laravel Cashier (Stripe)** | Subscription billing for Stripe |
| **Laravel Cashier (Paddle)** | Subscription billing for Paddle |
| **omnipay/omnipay** | Multi-gateway PHP library |

---

## Concepts to Master

- **PCI DSS compliance** — never store raw card numbers; always tokenize
- **Tokenization** — gateway returns a token; you store the token
- **Webhooks** — payment events (`charge.succeeded`, `subscription.updated`) come back via webhooks; verify signatures
- **Idempotency keys** — prevent double-charges on retries
- **Strong Customer Authentication (SCA / 3DS)** — EU regulatory requirement; Stripe handles via Payment Intents
- **Subscription lifecycle** — trial, active, past_due, canceled, etc.
- **Proration** — partial refunds when changing plans mid-cycle
- **Dunning** — what happens when a card fails (retry schedule, emails)
- **Refunds vs Chargebacks** — refund (you initiate), chargeback (customer disputes via bank)
- **Merchant of Record (MoR)** — they collect tax + handle compliance for you (Paddle/LemonSqueezy)
- **Payment intent vs Setup intent** — Stripe's modern flow concepts
- **Saved cards / Customer objects** — letting users pay with one click next time
