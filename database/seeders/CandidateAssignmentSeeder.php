<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidateAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['completed', 'completed', 'completed', 'active', 'cancelled'];
        
        // Create historical assignments for first 40 candidates
        for ($candidateId = 1; $candidateId <= 40; $candidateId++) {
            $numAssignments = rand(1, 5);
            
            for ($i = 0; $i < $numAssignments; $i++) {
                $status = $statuses[array_rand($statuses)];
                $startDate = now()->subDays(rand(30, 730));
                
                // Active assignments have no end date
                $endDate = $status === 'active' ? null : $startDate->copy()->addDays(rand(30, 180));
                
                \Illuminate\Support\Facades\DB::table('candidate_assignments')->insert([
                    'candidate_id' => $candidateId,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'job_id' => rand(1, 10),
                    'status' => $status,
                    'rating' => $status === 'completed' ? rand(3, 5) : null,
                    'notes' => $status === 'completed' ? 'Successfully completed assignment' : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
