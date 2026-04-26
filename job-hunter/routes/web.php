<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobListingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [HomeController::class, 'test']);

Route::get('/jobs/top',  [JobListingController::class, 'top']);
Route::get('/jobs/{id}', [JobListingController::class, 'show']);
