<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;

class JobListingController extends Controller
{
    public function top()
    {
        $jobs = Cache::get('jobs.top');

        if ($jobs !== null) {
            return view('jobs.top', ['jobs' => $jobs]);
        }

        $lock = Cache::lock('jobs.top.lock', 10);

        try {
            $jobs = $lock->block(5, function () {
                return Cache::remember('jobs.top', 120, function () {
                    return JobListing::orderByDesc('views')->limit(5)->get();
                });
            });
        } catch (LockTimeoutException) {
            $jobs = JobListing::orderByDesc('views')->limit(5)->get();
        }

        return view('jobs.top', ['jobs' => $jobs]);
    }

    public function show($id)
    {
        $job = JobListing::findOrFail($id);
        $job->increment('views');

        return view('jobs.show', ['job' => $job]);
    }
}
