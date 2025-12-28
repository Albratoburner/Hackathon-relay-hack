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
    Route::get('/create', [JobController::class, 'create'])->name('create');
    Route::post('/', [JobController::class, 'store'])->name('store');
    Route::get('/{id}', [JobController::class, 'show'])->name('show');
    Route::post('/{id}/rank', [JobController::class, 'rank'])->name('rank');
    Route::get('/{id}/confirm-hire/{candidateId}', [JobController::class, 'confirmHire'])->name('confirmHire');
    Route::post('/{id}/assign', [JobController::class, 'assign'])->name('assign');
});

// Candidate listing and profile
Route::get('/candidates', [CandidateWebController::class, 'index'])->name('candidates.index');
Route::get('/candidates/create', [CandidateWebController::class, 'create'])->name('candidates.create');
Route::post('/candidates', [CandidateWebController::class, 'store'])->name('candidates.store');
Route::get('/candidates/{id}', [CandidateWebController::class, 'show'])->name('candidates.show');
Route::get('/candidates/{id}/add-skills', [CandidateWebController::class, 'addSkills'])->name('candidates.addSkills');
Route::post('/candidates/{id}/skills', [CandidateWebController::class, 'storeSkills'])->name('candidates.storeSkills');
Route::post('/candidates/{id}/toggle-active', [CandidateWebController::class, 'toggleActive'])->name('candidates.toggleActive');

// Client View Routes
Route::prefix('client/{recruiterId}')->name('client.')->group(function () {
    Route::get('/jobs', [ClientController::class, 'jobs'])->name('jobs');
    Route::get('/jobs/{jobId}/candidates', [ClientController::class, 'rankedCandidates'])->name('candidates');
});
