<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /**
     * Show the form for creating a new candidate.
     */
    public function create()
    {
        return view('candidates.create');
    }

    /**
     * Store a newly created candidate.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:candidates,email',
            'phone' => 'nullable|string|max:20',
            'location' => 'required|string|max:50',
            'availability_type' => 'required|string|in:full_time,part_time,contract,temporary',
            'total_experience_years' => 'required|integer|min:0',
            'bio' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Set defaults
        $validated['is_active'] = $validated['is_active'] ?? true;

        // Get next ID
        $maxId = DB::table('candidates')->max('candidate_id');
        $validated['candidate_id'] = ($maxId ?? 0) + 1;

        $candidate = Candidate::create($validated);

        return redirect()->route('candidates.show', $candidate->candidate_id)
            ->with('success', 'Candidate created successfully!');
    }
}
