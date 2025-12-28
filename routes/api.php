<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\JobOrderController;
use App\Http\Controllers\CandidateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Job Order routes
Route::get('/jobs', [JobOrderController::class, 'index']);
Route::get('/jobs/{id}', [JobOrderController::class, 'show']);
Route::post('/jobs/{jobId}/rank', [RankingController::class, 'rank']);

// Candidate routes
Route::get('/candidates/{id}', [CandidateController::class, 'show']);
