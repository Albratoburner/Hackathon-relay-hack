<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    /**
     * Call the stored procedure to rank candidates for a job.
     * Expects optional JSON body: { "top_n": 10, "weights": { ... } }
     */
    public function rank(Request $request, $jobId)
    {
        $data = $request->validate([
            'top_n' => 'sometimes|integer|min:1',
            'weights' => 'sometimes|array'
        ]);

        $topN = $data['top_n'] ?? 20;
        $weightsJson = isset($data['weights']) ? json_encode($data['weights']) : null;

        // Call the stored procedure. Using select because it returns a result set.
        $bindings = [$jobId, $topN, $weightsJson];
        $rows = DB::select('EXEC sp_RankCandidatesForJob @JobId = ?, @TopN = ?, @ScoringWeightsJson = ?', $bindings);

        return response()->json($rows);
    }
}
