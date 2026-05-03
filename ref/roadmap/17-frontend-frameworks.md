# Frontend Frameworks

> JavaScript frameworks for building interactive UIs. Even if you're a backend dev, modern Laravel jobs increasingly expect at least one of these.

---

## 1. React — Popularity: 10/10

**Purpose:**
Facebook's UI library. Component-based, JSX, hooks. Largest ecosystem of any frontend framework. Used everywhere — from simple SPAs to complex enterprise apps.

**Alternatives:** (see below)

**When to learn it:** intermediate — biggest job market.

---

## 2. Next.js — Popularity: 10/10

**Purpose:**
React meta-framework from Vercel. Adds server-side rendering (SSR), static site generation (SSG), API routes, file-based routing, App Router with React Server Components. The default for serious React apps now.

**When to learn it:** intermediate — pair with React.

---

## 3. Vue.js — Popularity: 9/10

**Purpose:**
Progressive JS framework. Easier to learn than React for many. Popular in PHP/Laravel ecosystem (Inertia.js + Vue is common). Single-file components (`.vue`).

**Alternatives:**
- See React if avoiding Vue.

**When to learn it:** intermediate — Jobs 2, 3, 6 mention Vue/Nuxt.

---

## 4. Nuxt.js — Popularity: 8/10

**Purpose:**
Vue meta-framework. SSR, SSG, file-based routing. The "Next.js for Vue."

**When to learn it:** intermediate — pair with Vue.

---

## 5. Svelte / SvelteKit — Popularity: 7/10

**Purpose:**
Compiler-based framework. No virtual DOM — Svelte compiles to vanilla JS. Less code to write. SvelteKit is the meta-framework. Loved by developers who try it.

**When to learn it:** advanced — gaining ground but smaller job market.

---

## 6. Angular — Popularity: 7/10

**Purpose:**
Google's full-featured framework. TypeScript-first, opinionated (RxJS, DI, modules). Heavy but standardized. Common in enterprise.

**When to learn it:** advanced — only for enterprise/Angular shops.

---

## 7. SolidJS — Popularity: 4/10

**Purpose:**
React-like syntax with Svelte-like fine-grained reactivity. Very fast, smaller ecosystem.

**When to learn it:** advanced — niche but innovative.

---

## 8. Astro — Popularity: 7/10

**Purpose:**
Content-first framework. Ships zero JS by default; sprinkles in islands of interactivity. Excellent for blogs, marketing sites, docs. Multi-framework — embed React/Vue/Svelte components.

**When to learn it:** intermediate — for content sites.

---

## 9. Inertia.js — Popularity: 7/10 (in Laravel ecosystem)

**Purpose:**
"Modern monolith" — write Laravel routes/controllers as usual, render Vue/React/Svelte components without building a separate API. Wildly popular in Laravel jobs.

**When to learn it:** beginner — the easiest Vue/React on-ramp for Laravel devs.

---

## 10. Livewire — Popularity: 8/10 (in Laravel ecosystem)

**Purpose:**
Build reactive interfaces in Laravel using PHP classes — no JavaScript framework needed. Pairs with Alpine.js for sprinkles. Created by Laravel team.

**When to learn it:** beginner — staying-in-PHP option.

---

## 11. Alpine.js — Popularity: 7/10

**Purpose:**
Tiny (~15KB) JS framework for sprinkling interactivity into server-rendered HTML. Like jQuery but declarative. Pairs with Livewire and Blade.

**When to learn it:** beginner — fast win for Laravel devs.

---

## 12. HTMX — Popularity: 7/10

**Purpose:**
Add AJAX, WebSockets, SSE to plain HTML via attributes. No JS framework required. Server returns HTML fragments. Resurgence of "hypermedia" approach.

**When to learn it:** intermediate — pairs perfectly with Laravel/Blade.

---

## State Management Libraries (for React/Vue)

| Tool | Popularity | Purpose |
|---|---|---|
| **Zustand** | 9/10 | Modern minimal React state library |
| **Redux Toolkit** | 8/10 | Classic React state, with less boilerplate |
| **Jotai / Recoil** | 6/10 | Atom-based state |
| **Pinia** | 9/10 | Vue's official state library (replaced Vuex) |
| **TanStack Query** | 9/10 | Async/server state for React/Vue/Solid |

---

## Build Tools

| Tool | Popularity | Notes |
|---|---|---|
| **Vite** | 10/10 | Modern dev server + builder; default in new projects |
| **Webpack** | 7/10 | Old guard; still everywhere |
| **esbuild** | 8/10 | Used inside many tools |
| **Turbopack** | 6/10 | Vercel's Rust-based bundler |
| **Bun** | 7/10 | Runtime + bundler + test runner |
