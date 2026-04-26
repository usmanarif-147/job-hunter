# Stage 0 — The Example

## The example
Build a **"Top 5 Most-Viewed Jobs"** page.

A visitor opens `/jobs/top` and sees the 5 job listings with the highest view count.
Every time someone opens a single job at `/jobs/{id}`, that job's view count goes up by 1.

This is our base example. We will keep evolving it across all next stages.
Each stage will expose a real problem and teach one new concept that fixes it.
Redis is the final destination — not the starting point.

## What to build (Stage 0)

### 1. Migration
Create a `jobs_listing` table (don't use Laravel's default `jobs` table — that's for the queue).
Columns:
- `id`
- `title` (string)
- `company` (string)
- `description` (text)
- `views` (unsigned integer, default 0)
- timestamps

### 2. Model
`App\Models\JobListing` mapped to `jobs_listing` table. Fillable: `title`, `company`, `description`.

### 3. Seeder
Insert ~20 fake job listings with random view counts so we have data to play with.

### 4. Controller
`JobListingController` with two methods:
- `top()` — returns the 5 jobs with highest `views`.
- `show($id)` — returns one job AND increments its `views` by 1.

### 5. Routes (in `routes/web.php`)
- `GET /jobs/top`  → `JobListingController@top`
- `GET /jobs/{id}` → `JobListingController@show`

### 6. Views (optional)
Plain Blade pages or just `return response()->json(...)`. Up to you — we are learning backend, not UI.

## Done when
- You can hit `/jobs/top` and see 5 jobs ordered by views (desc).
- You can hit `/jobs/1` a few times, refresh `/jobs/top`, and see the order change.

