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
        $query = Candidate::query();

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', 1);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', 0);
            }
            // 'all' shows both active and inactive
        } else {
            // Default: show only active candidates
            $query->where('is_active', 1);
        }

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
        $validated['is_active'] = isset($validated['is_active']) ? (bool)$validated['is_active'] : 1;

        // Get next ID
        $maxId = DB::table('candidates')->max('candidate_id');
        $validated['candidate_id'] = ($maxId ?? 0) + 1;
        $newCandidateId = $validated['candidate_id'];

        $candidate = Candidate::create($validated);

        return redirect()->route('candidates.show', $newCandidateId)
            ->with('success', 'Candidate created successfully!');
    }

    /**
     * Show form to add skills to candidate.
     */
    public function addSkills($id)
    {
        $candidate = Candidate::findOrFail($id);
        $skills = \App\Models\Skill::with('category')->orderBy('skill_name')->get();
        $skillLevels = \App\Models\SkillLevel::orderBy('level_id')->get();
        $existingSkills = $candidate->skills->pluck('skill_id')->toArray();

        return view('candidates.add-skills', compact('candidate', 'skills', 'skillLevels', 'existingSkills'));
    }

    /**
     * Store skills for a candidate.
     */
    public function storeSkills(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        $validated = $request->validate([
            'skills' => 'required|array|min:1',
            'skills.*.skill_id' => 'required|exists:skills,skill_id',
            'skills.*.level_id' => 'required|exists:skill_levels,level_id',
            'skills.*.years_of_experience' => 'nullable|integer|min:0|max:50',
            'skills.*.proficiency_rating' => 'nullable|integer|min:1|max:5',
        ]);

        // Delete existing skills for fresh update
        \App\Models\CandidateSkill::where('candidate_id', $id)->delete();

        // Insert new skills
        foreach ($validated['skills'] as $skillData) {
            \App\Models\CandidateSkill::create([
                'candidate_id' => $id,
                'skill_id' => $skillData['skill_id'],
                'level_id' => $skillData['level_id'],
                'years_of_experience' => $skillData['years_of_experience'] ?? 0,
                'proficiency_level' => $skillData['proficiency_rating'] ?? 3,
            ]);
        }

        return redirect()->route('candidates.show', $id)
            ->with('success', 'Skills updated successfully!');
    }

    /**
     * Toggle candidate active/inactive status.
     */
    public function toggleActive($id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->is_active = !$candidate->is_active;
        $candidate->save();

        $status = $candidate->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('candidates.show', $id)
            ->with('success', "Candidate {$status} successfully!");
    }
}
