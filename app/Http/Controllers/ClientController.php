<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\RankingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display jobs for a specific client (recruiter).
     */
    public function jobs($recruiterId)
    {
        $jobs = JobOrder::where('recruiter_id', $recruiterId)
            ->orderBy('posted_date', 'desc')
            ->get();

        return view('client.jobs', compact('jobs', 'recruiterId'));
    }

    /**
     * Display ranked candidates for a client's job.
     */
    public function rankedCandidates($recruiterId, $jobId)
    {
        $job = JobOrder::where('job_id', $jobId)
            ->where('recruiter_id', $recruiterId)
            ->firstOrFail();

        // Get latest ranking results with candidate details
        $latestExecution = RankingHistory::where('job_id', $jobId)
            ->max('execution_date');

        $rankings = RankingHistory::with('candidate')
            ->where('job_id', $jobId)
            ->where('execution_date', $latestExecution)
            ->orderBy('rank_position', 'asc')
            ->limit(20)
            ->get();

        return view('client.candidates', compact('job', 'rankings', 'recruiterId'));
    }
}
