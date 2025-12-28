<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidatePerformanceReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all completed assignments
        $completedAssignments = \Illuminate\Support\Facades\DB::table('candidate_assignments')
            ->where('status', 'completed')
            ->get();
        
        $completionStatuses = ['completed', 'completed', 'completed', 'partial'];
        
        foreach ($completedAssignments as $assignment) {
            $rating = rand(3, 5);
            $completionStatus = $completionStatuses[array_rand($completionStatuses)];
            
            \Illuminate\Support\Facades\DB::table('candidate_performance_reviews')->insert([
                'candidate_id' => $assignment->candidate_id,
                'assignment_id' => $assignment->assignment_id,
                'rating' => $rating,
                'completion_status' => $completionStatus,
                'feedback' => $rating >= 4 ? 'Excellent work, exceeded expectations.' : 'Good performance, met requirements.',
                'review_date' => now()->subDays(rand(1, 365)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
