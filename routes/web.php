<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CandidateWebController;
use App\Http\Controllers\ClientController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Dashboard
Route::get('/', [HomeController::class, 'index'])->name('home');

// Recruiter/Internal User Routes
Route::prefix('jobs')->name('jobs.')->group(function () {
    Route::get('/', [JobController::class, 'index'])->name('index');
    Route::get('/{id}', [JobController::class, 'show'])->name('show');
    Route::post('/{id}/rank', [JobController::class, 'rank'])->name('rank');
});

// Candidate Profile
Route::get('/candidates/{id}', [CandidateWebController::class, 'show'])->name('candidates.show');

// Client View Routes
Route::prefix('client/{recruiterId}')->name('client.')->group(function () {
    Route::get('/jobs', [ClientController::class, 'jobs'])->name('jobs');
    Route::get('/jobs/{jobId}/candidates', [ClientController::class, 'rankedCandidates'])->name('candidates');
});
