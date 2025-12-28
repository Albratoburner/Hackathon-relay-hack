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
        $skillsJson = isset($validated['required_skills']) ? json_encode($validated['required_skills']) : null;

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
