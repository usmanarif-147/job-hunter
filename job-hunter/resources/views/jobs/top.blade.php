<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Top 5 Most-Viewed Jobs</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 720px; margin: 2rem auto; padding: 0 1rem; color: #222; }
        h1 { margin-bottom: 1.5rem; }
        .job { border: 1px solid #ddd; border-radius: 6px; padding: 1rem; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
        .job-info h3 { margin: 0 0 .25rem; }
        .job-info .company { color: #666; font-size: .9rem; }
        .job-info .views { color: #888; font-size: .85rem; margin-top: .25rem; }
        .btn { background: #2563eb; color: #fff; border: none; padding: .5rem 1rem; border-radius: 4px; text-decoration: none; font-size: .9rem; cursor: pointer; }
        .btn:hover { background: #1d4ed8; }
    </style>
</head>
<body>
    <h1>Top 5 Most-Viewed Jobs</h1>
    <h2> {{ gethostname() }} </h2>
    @forelse ($jobs as $job)
        <div class="job">
            <div class="job-info">
                <h3>{{ $job->title }}</h3>
                <div class="company">{{ $job->company }}</div>
                <div class="views">{{ $job->views }} views</div>
            </div>
            <a class="btn" href="{{ url('/jobs/'.$job->id) }}">View</a>
        </div>
    @empty
        <p>No jobs yet. Run <code>php artisan migrate:fresh --seed</code>.</p>
    @endforelse
</body>
</html>
