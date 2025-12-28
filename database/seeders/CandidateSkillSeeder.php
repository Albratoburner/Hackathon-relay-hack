<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidateSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assign 3-8 random skills to each candidate
        for ($candidateId = 1; $candidateId <= 60; $candidateId++) {
            $numSkills = rand(3, 8);
            $assignedSkills = [];
            
            for ($i = 0; $i < $numSkills; $i++) {
                $skillId = rand(1, 30);
                
                // Avoid duplicate skills for same candidate
                if (in_array($skillId, $assignedSkills)) {
                    continue;
                }
                
                $assignedSkills[] = $skillId;
                
                \Illuminate\Support\Facades\DB::table('candidate_skills')->insert([
                    'candidate_id' => $candidateId,
                    'skill_id' => $skillId,
                    'proficiency_level' => rand(2, 5), // 2-5 scale
                    'level_id' => rand(2, 5), // Intermediate to Master
                    'years_of_experience' => rand(1, 8),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
