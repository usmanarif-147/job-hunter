<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $job->title }}</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 720px; margin: 2rem auto; padding: 0 1rem; color: #222; }
        .back { display: inline-block; margin-bottom: 1rem; color: #2563eb; text-decoration: none; }
        .back:hover { text-decoration: underline; }
        h1 { margin: 0 0 .25rem; }
        .company { color: #666; margin-bottom: .5rem; }
        .views { color: #888; font-size: .9rem; margin-bottom: 1.5rem; }
        .description { line-height: 1.6; }
    </style>
</head>
<body>
    <a class="back" href="{{ url('/jobs/top') }}">&larr; Back to top jobs</a>

    <h1>{{ $job->title }}</h1>
    <div class="company">{{ $job->company }}</div>
    <div class="views">{{ $job->views }} views</div>

    <p class="description">{{ $job->description }}</p>
</body>
</html>
