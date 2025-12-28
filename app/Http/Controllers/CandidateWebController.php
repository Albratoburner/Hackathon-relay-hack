<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateWebController extends Controller
{
    /**
     * Display the specified candidate profile.
     */
    public function show($id)
    {
        $candidate = Candidate::with([
            'skills.skill.category',
            'skills.level',
            'assignments' => function($query) {
                $query->orderBy('start_date', 'desc')->limit(10);
            }
        ])->findOrFail($id);

        return view('candidates.show', compact('candidate'));
    }
}
