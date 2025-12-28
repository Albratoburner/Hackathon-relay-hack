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

    /**
     * Display a paginated listing of candidates.
     */
    public function index(Request $request)
    {
        $query = Candidate::query()->where('is_active', 1);

        if ($request->filled('search')) {
            $q = $request->input('search');
            $query->where('full_name', 'like', "%{$q}%");
        }

        $candidates = $query->with(['skills.skill', 'skills.level'])
            ->orderBy('full_name')
            ->paginate(15);

        return view('candidates.index', compact('candidates'));
    }
}
