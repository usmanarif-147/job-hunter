<?php

namespace App\Http\Controllers;

use App\Models\JobListing;

class JobListingController extends Controller
{
    public function top()
    {
        $jobs = JobListing::orderByDesc('views')->limit(5)->get();

        return view('jobs.top', ['jobs' => $jobs]);
    }

    public function show($id)
    {
        $job = JobListing::findOrFail($id);
        $job->increment('views');

        return view('jobs.show', ['job' => $job]);
    }
}
