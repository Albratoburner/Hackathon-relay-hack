<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            ['job_id' => 1, 'job_title' => 'Senior Full Stack Developer', 'min_experience_years' => 5, 'preferred_location' => 'New York', 'availability_type' => 'Immediate'],
            ['job_id' => 2, 'job_title' => 'Backend PHP Developer', 'min_experience_years' => 3, 'preferred_location' => 'San Francisco', 'availability_type' => '2weeks'],
            ['job_id' => 3, 'job_title' => 'React Frontend Developer', 'min_experience_years' => 2, 'preferred_location' => 'Remote', 'availability_type' => 'Immediate'],
            ['job_id' => 4, 'job_title' => 'Database Administrator', 'min_experience_years' => 4, 'preferred_location' => 'Chicago', 'availability_type' => '1month'],
            ['job_id' => 5, 'job_title' => 'DevOps Engineer', 'min_experience_years' => 3, 'preferred_location' => 'Austin', 'availability_type' => 'Immediate'],
            ['job_id' => 6, 'job_title' => 'Cloud Architect', 'min_experience_years' => 7, 'preferred_location' => 'Seattle', 'availability_type' => '2weeks'],
            ['job_id' => 7, 'job_title' => 'Junior Developer', 'min_experience_years' => 1, 'preferred_location' => 'Boston', 'availability_type' => 'Immediate'],
            ['job_id' => 8, 'job_title' => 'Tech Lead', 'min_experience_years' => 8, 'preferred_location' => 'New York', 'availability_type' => '1month'],
            ['job_id' => 9, 'job_title' => 'Node.js Developer', 'min_experience_years' => 3, 'preferred_location' => 'Remote', 'availability_type' => 'Immediate'],
            ['job_id' => 10, 'job_title' => '.NET Developer', 'min_experience_years' => 4, 'preferred_location' => 'Los Angeles', 'availability_type' => '2weeks'],
        ];

        foreach ($jobs as $job) {
            \Illuminate\Support\Facades\DB::table('job_orders')->insert([
                'job_id' => $job['job_id'],
                'job_title' => $job['job_title'],
                'job_description' => 'Looking for experienced ' . $job['job_title'] . ' to join our team.',
                'min_experience_years' => $job['min_experience_years'],
                'preferred_location' => $job['preferred_location'],
                'availability_type' => $job['availability_type'],
                'max_results' => 10,
                'posted_date' => now()->subDays(rand(1, 30)),
                'status' => 'open',
                'recruiter_id' => rand(100, 105),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
