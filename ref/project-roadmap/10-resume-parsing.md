# 10 — Resume Parsing & Skill Matching

## What this service does

When an applicant uploads a resume, we want to:

1. Extract the text from PDF / DOCX
2. Identify candidate skills mentioned in the text
3. Compute a **match score** against the job's required skills
4. Save the result so the company sees applicants ranked by match

All of this happens **asynchronously** — the user's "Apply" click returns instantly. Parsing happens in the background.

## Why a separate service in Python

PHP's PDF/DOCX libraries are weak. Python has:

- `pdfplumber` / `pdfminer.six` — PDF text extraction
- `python-docx` / `docx2txt` — DOCX extraction
- `spacy` — fast NLP for skill recognition
- `scikit-learn` / `rapidfuzz` — text similarity / fuzzy matching

Wrapped in **FastAPI** for a clean HTTP interface; can also be invoked via Kafka consumer.

## The flow

```
Applicant uploads resume → S3
         │
         ▼
Application created (Core API)
         │
         ▼
"ApplicationCreated" event emitted to Kafka / Redis Streams
         │
         ▼
Resume Parser consumes event
         │
         ├─ Downloads resume from S3
         ├─ Extracts text (PDF or DOCX)
         ├─ Extracts skills (NER + dictionary lookup)
         ├─ Fetches job's required_skills from Core API
         ├─ Computes match score (Jaccard / weighted overlap)
         └─ Writes back to Core API: PATCH /applications/{id}/parsed
                  - parsed_text (truncated)
                  - extracted_skills[]
                  - match_score (0–100)
                  - parsed_at
```

## Skill extraction strategy

Two layers, combined:

1. **Dictionary lookup** — maintain a canonical skill list (`skills` table, ~5,000 entries: PHP, Laravel, Postgres, Docker, AWS, ...). Match each token / phrase against this list with fuzzy matching (`rapidfuzz`) to handle typos.
2. **Named entity recognition (spaCy)** — finds technology / skill names not in the dictionary; flag for admin review and possibly add to canonical list.

The dictionary is **the truth**; NER is a discovery tool. This keeps results predictable.

## Match score

Simple v1 formula: **Jaccard similarity** between (job's required skills) and (extracted skills), with weights:

- A "must-have" skill weighs more than a "nice-to-have"
- Skills must come from the canonical list (no free-text noise)

Output is 0–100. Companies sort applicants by score.

## Failure handling

| Failure | What happens |
|---|---|
| Resume isn't a supported format | Parsing skipped, status = `unsupported_format`, company sees raw file |
| Resume is encrypted PDF | Status = `encrypted`, company sees raw file |
| Parser service crashes | Job retries 3x; final failure → status = `parse_failed` |
| Parser is slow / queue is long | Application is still visible to the company immediately, with a "parsing..." badge that updates when done |

The user experience never breaks — parsing failures degrade gracefully.

## Privacy & retention

- Resume files are stored in S3 with **server-side encryption (SSE-S3)**
- Parsed text is stored in Postgres; the **raw PDF** stays in S3
- Applicants can delete their account → all resumes + parsed data purged within 7 days (GDPR-style)
- Parsed text is **not** indexed in Meilisearch (privacy-sensitive)

## Throughput planning

- Average resume = ~300KB, parses in 1–3 seconds on a modest CPU
- Peak: thousands of applications per minute → a pool of 5–20 parser workers handles it
- Workers scale horizontally — they're stateless

## Why not run parsing inline (synchronously)?

- It would block the apply endpoint for 1–3 seconds → timeouts under load
- It would couple the Core API's reliability to the parser
- It would make Core API harder to scale
- Async parsing is the right answer

## Future extensions (defer)

- LLM-based summarization (e.g., "TL;DR of this resume")
- Semantic match using vector embeddings (pgvector + a small embedding model)
- Auto-categorization (years of experience, seniority level, location parsing)

These are obvious value-adds but not v1.
