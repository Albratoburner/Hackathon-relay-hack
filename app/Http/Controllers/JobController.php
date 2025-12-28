<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
