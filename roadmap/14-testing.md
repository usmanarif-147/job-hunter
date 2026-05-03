# Testing

> Automated checks that prove your code works. Different tools for different layers: unit (one function), integration (with DB), e2e (whole user journey in a browser), load (system under stress).

---

## 1. Pest (PHP) — Popularity: 9/10

**Purpose:**
Modern testing framework for PHP, built on PHPUnit. Beautiful, expressive syntax (`it('does X', fn() => ...)`), great for Laravel. Becoming the new default in Laravel projects.

**Alternatives:**
- **PHPUnit** — 10/10 — The PHP testing standard since forever; what Pest is built on. Still default in many shops.

**When to learn it:** beginner — Job 4 lists Pest/PHPUnit as required.

---

## 2. PHPUnit — Popularity: 10/10

**Purpose:**
The de facto PHP testing framework. xUnit-style. Used by Laravel internally and most PHP projects.

**When to learn it:** beginner — you must know this if you write Laravel.

---

## 3. Playwright — Popularity: 9/10

**Purpose:**
Microsoft's modern end-to-end browser testing tool. Drives real Chromium, Firefox, WebKit. Auto-waits, parallel runs, video recording, code-gen tool. Used for testing entire user flows on real browsers.

**Alternatives:**
- **Cypress** — 8/10 — Same niche, beautiful UI, slower than Playwright; was the leader before Playwright.
- **Selenium** — 6/10 — The grandfather of browser testing; flaky, slow, but multi-language and ubiquitous.
- **Puppeteer** — 7/10 — Google's tool; Playwright is its spiritual successor.

**When to learn it:** intermediate — you've already touched it; deepen here.

---

## 4. Jest — Popularity: 9/10

**Purpose:**
JavaScript testing framework (originally from Facebook). Default for React/Node projects. Snapshot testing, mocks, watch mode.

**Alternatives:**
- **Vitest** — 9/10 — Modern Vite-native test runner; faster than Jest. Default in new Vite projects.
- **Mocha** — 5/10 — Older, still around.
- **Jasmine** — 4/10 — Pre-Jest era.

**When to learn it:** intermediate — for any JS/TS work.

---

## 5. Laravel Dusk — Popularity: 6/10

**Purpose:**
Laravel's own browser testing tool. Uses ChromeDriver. Simpler than Playwright but limited to Chromium and PHP-only. Many teams now prefer Playwright.

**When to learn it:** beginner — if staying purely Laravel.

---

## 6. k6 (Grafana) — Popularity: 8/10

**Purpose:**
Modern load testing tool. Tests are JS scripts. Outputs to Grafana/InfluxDB. Designed for developers, not just QA. Replaces JMeter in many modern teams.

**Alternatives:**
- **Apache JMeter** — 7/10 — Veteran load tester; Java GUI; common in enterprise.
- **Gatling** — 6/10 — Scala-based, high performance.
- **Locust** — 6/10 — Python-based, very simple.
- **Artillery** — 5/10 — Node.js-based, similar to k6.

**When to learn it:** intermediate — for performance-critical apps.

---

## 7. Postman / Newman / Bruno — Popularity: 9/10

**Purpose:**
**Postman** is the de facto API testing/exploration GUI. **Newman** runs Postman collections in CI. **Bruno** is the open-source alternative storing collections as files in Git.

**When to learn it:** beginner — you probably already use Postman.

---

## 8. Static Analysis Tools (PHP)

| Tool | Popularity | Purpose |
|---|---|---|
| **PHPStan** | 9/10 | Static type analysis; finds bugs without running code |
| **Psalm** | 7/10 | Same niche; from Vimeo |
| **PHP CS Fixer** | 8/10 | Auto-fixes code style |
| **Laravel Pint** | 8/10 | Laravel's wrapper around PHP CS Fixer |
| **Rector** | 7/10 | Automated upgrades and refactoring |

---

## 9. TestContainers — Popularity: 7/10

**Purpose:**
Library that spins up real Docker containers (Postgres, Redis, Kafka) for integration tests, then tears them down after. Available in Java, Node, Go, Python, .NET. Better than mocking for integration accuracy.

**When to learn it:** intermediate — interview-impressive.

---

## 10. Mutation Testing

**Purpose:**
Tests your tests by introducing small bugs (mutations) and checking if your tests catch them. Reveals weak tests.

**Tools:**
- **Infection** — 6/10 — PHP mutation testing.
- **Stryker** — 5/10 — JS/TS mutation testing.

**When to learn it:** advanced.

---

## Concepts to Master

- **Unit / Integration / E2E / Smoke / Regression** — types of tests
- **AAA pattern** — Arrange / Act / Assert
- **Test pyramid** — many unit, fewer integration, fewest e2e
- **Mocks / Stubs / Fakes / Spies** — test doubles
- **Fixtures / Factories / Seeders** — preparing test data
- **CI integration** — every commit runs tests
- **Code coverage** — % of code exercised by tests; not the only metric
- **Flaky tests** — pass sometimes; biggest team morale killer
- **Snapshot tests** — compare output to a saved version
- **Contract testing** — verify API consumers and providers agree (e.g., Pact)
