<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Http\Resources\CandidateResource;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    /**
     * Display the specified candidate.
     */
    public function show($id)
    {
        $candidate = Candidate::with([
            'skills.skill.category',
            'skills.level',
            'assignments'
        ])->findOrFail($id);

        return new CandidateResource($candidate);
    }
}
