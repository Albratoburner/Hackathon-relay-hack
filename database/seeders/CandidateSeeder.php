<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = ['New York', 'San Francisco', 'Chicago', 'Boston', 'Austin', 'Seattle', 'Los Angeles', 'Remote'];
        $availabilityTypes = ['Immediate', '2weeks', '1month', 'Notice'];
        
        $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa', 'James', 'Maria', 
                       'William', 'Jennifer', 'Richard', 'Linda', 'Thomas', 'Patricia', 'Charles', 'Elizabeth', 'Daniel', 'Susan'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
                     'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin'];

        for ($i = 1; $i <= 60; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = $firstName . ' ' . $lastName;
            
            \Illuminate\Support\Facades\DB::table('candidates')->insert([
                'candidate_id' => $i,
                'full_name' => $fullName,
                'email' => strtolower($firstName . '.' . $lastName . $i . '@example.com'),
                'phone' => '+1-555-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'location' => $locations[array_rand($locations)],
                'availability_type' => $availabilityTypes[array_rand($availabilityTypes)],
                'total_experience_years' => rand(1, 15),
                'bio' => 'Experienced professional with ' . rand(1, 15) . ' years in software development.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
