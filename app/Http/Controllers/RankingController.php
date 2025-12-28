<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    /**
     * Call the stored procedure to rank candidates for a job.
     * Expects optional JSON body: { "max_results": 10, "required_skills": [...] }
     */
    public function rank(Request $request, $jobId)
    {
        $data = $request->validate([
            'max_results' => 'sometimes|integer|min:1',
            'required_skills' => 'sometimes|array'
        ]);

        $maxResults = $data['max_results'] ?? 20;
        $skillsJson = isset($data['required_skills']) ? json_encode($data['required_skills']) : null;

        // Call the stored procedure. Using select because it returns a result set.
        $bindings = [$jobId, $skillsJson, $maxResults];
        $rows = DB::select('EXEC sp_RankCandidatesForJob @JobId = ?, @RequiredSkillsJson = ?, @MaxResults = ?', $bindings);

        return response()->json($rows);
    }
}
