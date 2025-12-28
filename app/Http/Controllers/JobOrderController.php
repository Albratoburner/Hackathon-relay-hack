<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Http\Resources\JobOrderResource;
use Illuminate\Http\Request;

class JobOrderController extends Controller
{
    /**
     * Display a listing of job orders.
     */
    public function index()
    {
        $jobs = JobOrder::withCount('assignments')
            ->orderBy('posted_date', 'desc')
            ->paginate(15);

        return JobOrderResource::collection($jobs);
    }

    /**
     * Display the specified job order.
     */
    public function show($id)
    {
        $job = JobOrder::with('rankings')
            ->withCount('assignments')
            ->findOrFail($id);

        return new JobOrderResource($job);
    }
}
