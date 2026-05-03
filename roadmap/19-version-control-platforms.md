# Version Control Platforms

> Git is the underlying tech (you already use it). These are the **platforms** that host your repos and add code review, CI/CD, issue tracking, and collaboration on top.

---

## 1. GitHub — Popularity: 10/10

**Purpose:**
The largest platform for hosting Git repos. Owned by Microsoft. Includes pull requests, issues, projects, GitHub Actions (CI/CD), GitHub Packages (artifact registry), Copilot (AI), Codespaces (cloud dev environments). The default for open-source and most companies.

**Alternatives:** (see below)

**When to learn it:** beginner — you already use it; deepen with Actions, Projects, Codespaces.

---

## 2. GitLab — Popularity: 9/10

**Purpose:**
All-in-one DevOps platform. Self-hostable (very popular in regulated/enterprise) or managed (gitlab.com). Includes Git hosting + CI/CD + container registry + security scanning + monitoring + wiki — all built-in. Strong CI/CD compared to GitHub.

**When to learn it:** intermediate — common in enterprise.

---

## 3. Bitbucket (Atlassian) — Popularity: 6/10

**Purpose:**
Atlassian's Git platform. Tight integration with Jira and Confluence. Common in companies already using Atlassian stack. Bitbucket Pipelines for CI/CD.

**When to learn it:** beginner — only if your company uses it.

---

## 4. Gitea — Popularity: 6/10

**Purpose:**
Self-hosted lightweight GitHub alternative. Single Go binary. Great for internal corporate Git or homelab.

**Alternatives:**
- **Forgejo** — 5/10 — Community fork of Gitea after governance issues.

**When to learn it:** advanced — for self-hosted setups.

---

## 5. AWS CodeCommit — Popularity: 3/10

**Purpose:**
AWS-managed Git hosting. Less popular as AWS now recommends GitHub/GitLab. Often paired with CodePipeline/CodeBuild.

**When to learn it:** advanced — niche.

---

## 6. SourceHut — Popularity: 2/10

**Purpose:**
Email-based Git workflow. Minimalist, hacker-friendly. Niche.

**When to learn it:** skip unless you find it culturally interesting.

---

## Git Concepts to Master

You said you already know Git. These are the deeper / interview-grade topics worth practicing:

- **Branching strategies** — Git Flow, GitHub Flow, Trunk-based development
- **Rebase vs Merge** — rewriting vs preserving history
- **Interactive rebase** (`git rebase -i`) — squashing, reordering commits
- **Cherry-pick** — pulling one commit from another branch
- **Reflog** — recover from "I deleted that branch" disasters
- **Bisect** — binary search for the commit that introduced a bug
- **Stash** — set aside changes without committing
- **Submodules vs Subtrees** — embedding repos in repos
- **Hooks** — pre-commit, pre-push (Husky for Node, lefthook for any)
- **Conventional Commits** — `feat:`, `fix:`, `chore:` style
- **Semantic versioning** — `MAJOR.MINOR.PATCH`
- **Pull request reviews** — what to look for, how to comment
- **Force push safely** — `--force-with-lease` not `--force`
- **Signed commits** (GPG / SSH signing) — proves identity
- **Worktrees** — multiple branches checked out at once

---

## Code Review Tools (related)

| Tool | Popularity | Purpose |
|---|---|---|
| **GitHub PR reviews** | 10/10 | Built-in |
| **GitLab MR reviews** | 9/10 | Built-in |
| **Gerrit** | 4/10 | Heavyweight; used by Google/Android |
| **Reviewable** | 3/10 | Add-on for GitHub |
| **Graphite** | 4/10 | Stacked diffs workflow |
