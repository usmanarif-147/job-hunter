<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class JobListingController extends Controller
{
    public function top()
    {
        $jobs = Cache::remember('jobs.top', 60, function () {
            return JobListing::orderByDesc('views')->limit(5)->get();
        });

        return view('jobs.top', ['jobs' => $jobs]);
    }

    public function show($id)
    {
        $job = JobListing::findOrFail($id);
        $job->increment('views');

        return view('jobs.show', ['job' => $job]);
    }
}
