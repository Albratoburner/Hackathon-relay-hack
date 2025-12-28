<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\Candidate;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $stats = [
            'total_jobs' => JobOrder::where('status', 'open')->count(),
            'total_candidates' => Candidate::where('is_active', 1)->count(),
            'filled_positions' => JobOrder::where('status', 'filled')->count(),
            'pending_reviews' => JobOrder::where('status', 'reviewing')->count(),
            'recent_jobs' => JobOrder::whereIn('status', ['open', 'reviewing'])
                ->orderBy('posted_date', 'desc')
                ->limit(5)
                ->get()
        ];

        return view('dashboard', compact('stats'));
    }
}
