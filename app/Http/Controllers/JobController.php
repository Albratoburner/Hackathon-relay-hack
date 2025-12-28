<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\Skill;
use App\Models\CandidateAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class JobController extends Controller
{
    /**
     * Display a listing of jobs.
     */
    public function index(Request $request)
    {
        $query = JobOrder::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'open');
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('job_title', 'like', "%{$search}%")
                  ->orWhere('job_description', 'like', "%{$search}%");
            });
        }

        $jobs = $query->orderBy('posted_date', 'desc')->paginate(15);

        return view('jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new job.
     */
    public function create()
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created job.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:100',
            'job_description' => 'nullable|string',
            'min_experience_years' => 'required|integer|min:0',
            'preferred_location' => 'nullable|string|max:50',
            'availability_type' => 'required|string|in:full_time,part_time,contract,temporary',
            'max_results' => 'nullable|integer|min:1|max:50',
            'posted_date' => 'nullable|date',
            'status' => 'nullable|string|in:open,reviewing,filled',
        ]);

        // Set defaults
        $validated['posted_date'] = $validated['posted_date'] ?? Carbon::today();
        $validated['status'] = $validated['status'] ?? 'open';
        $validated['max_results'] = $validated['max_results'] ?? 10;

        // Get next ID
        $maxId = DB::table('job_orders')->max('job_id');
        $validated['job_id'] = ($maxId ?? 0) + 1;
        $newJobId = $validated['job_id'];

        $job = JobOrder::create($validated);

        return redirect()->route('jobs.show', $newJobId)
            ->with('success', 'Job created successfully!');
    }

    /**
     * Display the job details and ranking form.
     */
    public function show($id)
    {
        $job = JobOrder::findOrFail($id);
        $skills = Skill::with('category')->orderBy('skill_name')->get();

        return view('jobs.show', compact('job', 'skills'));
    }

    /**
     * Execute ranking and display results.
     */
    public function rank(Request $request, $id)
    {
        $job = JobOrder::findOrFail($id);

        // Validate job status
        if ($job->status !== 'open') {
            return redirect()->route('jobs.show', $id)
                ->with('error', 'Cannot rank candidates for a job that is not open.');
        }

        $validated = $request->validate([
            'max_results' => 'sometimes|integer|min:1|max:50',
            'required_skills' => 'sometimes|array'
        ]);

        $maxResults = $validated['max_results'] ?? 20;

        // Build RequiredSkills JSON in the object structure the stored procedure expects.
        $skillsJson = null;
        if (!empty($validated['required_skills'])) {
            $rawSkills = $validated['required_skills'];

            // If the form submitted numeric IDs (most likely), convert them to objects with defaults
            $skillsFromDb = Skill::whereIn('skill_id', $rawSkills)->get()->keyBy('skill_id');

            $payload = [];
            foreach ($rawSkills as $s) {
                // if it's already an associative array/object with skillName, use it as-is
                if (is_array($s) && isset($s['skillName'])) {
                    $payload[] = $s;
                    continue;
                }

                $skillName = null;
                if (is_numeric($s) && isset($skillsFromDb[$s])) {
                    $skillName = $skillsFromDb[$s]->skill_name;
                } elseif (is_string($s)) {
                    // allow strings containing a skill name
                    $skillName = $s;
                }

                if ($skillName) {
                    $payload[] = [
                        'skillName' => $skillName,
                        'weight' => 1,
                        'tier' => 'required',
                        'minLevel' => 1
                    ];
                }
            }

            $skillsJson = count($payload) ? json_encode($payload, JSON_UNESCAPED_UNICODE) : null;
        }

        try {
            // Call stored procedure
            $bindings = [$id, $skillsJson, $maxResults];
            $results = DB::select('EXEC sp_RankCandidatesForJob @JobId = ?, @RequiredSkillsJson = ?, @MaxResults = ?', $bindings);

            return view('jobs.results', compact('job', 'results', 'maxResults'));
        } catch (\Exception $e) {
            return redirect()->route('jobs.show', $id)
                ->with('error', 'Error executing ranking: ' . $e->getMessage());
        }
    }

    /**
     * Show confirmation page before hiring.
     */
    public function confirmHire($id, $candidateId)
    {
        $job = JobOrder::findOrFail($id);
        $candidate = \App\Models\Candidate::with([
            'skills.skill',
            'skills.level'
        ])->findOrFail($candidateId);

        // Try to get the ranking score if available
        $score = \App\Models\RankingHistory::where('job_id', $id)
            ->where('candidate_id', $candidateId)
            ->orderBy('execution_date', 'desc')
            ->first();

        return view('jobs.confirm-hire', compact('job', 'candidate', 'score'));
    }

    /**
     * Assign a candidate to a job and mark as filled.
     */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'candidate_id' => ['required', 'integer', Rule::exists('candidates', 'candidate_id')],
            'start_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $job = JobOrder::findOrFail($id);

        if ($job->status === 'filled') {
            return redirect()->route('jobs.show', $id)
                ->with('error', 'Job is already filled.');
        }

        DB::transaction(function() use ($request, $job) {
            CandidateAssignment::create([
                'candidate_id' => $request->input('candidate_id'),
                'job_id' => $job->job_id,
                'start_date' => $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::today(),
                'status' => 'active',
                'notes' => $request->input('notes'),
            ]);

            $job->status = 'filled';
            $job->save();
        });

        return redirect()->route('jobs.show', $id)
            ->with('success', 'Candidate assigned and job marked as filled.');
    }
}
