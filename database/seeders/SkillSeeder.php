<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Programming Languages
            ['skill_id' => 1, 'skill_name' => 'PHP', 'category_id' => 1],
            ['skill_id' => 2, 'skill_name' => 'JavaScript', 'category_id' => 1],
            ['skill_id' => 3, 'skill_name' => 'TypeScript', 'category_id' => 1],
            ['skill_id' => 4, 'skill_name' => 'Python', 'category_id' => 1],
            ['skill_id' => 5, 'skill_name' => 'Java', 'category_id' => 1],
            ['skill_id' => 6, 'skill_name' => 'C#', 'category_id' => 1],
            
            // Frameworks & Libraries
            ['skill_id' => 7, 'skill_name' => 'Laravel', 'category_id' => 2],
            ['skill_id' => 8, 'skill_name' => 'React', 'category_id' => 2],
            ['skill_id' => 9, 'skill_name' => 'Vue.js', 'category_id' => 2],
            ['skill_id' => 10, 'skill_name' => 'Angular', 'category_id' => 2],
            ['skill_id' => 11, 'skill_name' => 'Node.js', 'category_id' => 2],
            ['skill_id' => 12, 'skill_name' => '.NET Core', 'category_id' => 2],
            ['skill_id' => 13, 'skill_name' => 'Spring Boot', 'category_id' => 2],
            
            // Databases
            ['skill_id' => 14, 'skill_name' => 'SQL Server', 'category_id' => 3],
            ['skill_id' => 15, 'skill_name' => 'MySQL', 'category_id' => 3],
            ['skill_id' => 16, 'skill_name' => 'PostgreSQL', 'category_id' => 3],
            ['skill_id' => 17, 'skill_name' => 'MongoDB', 'category_id' => 3],
            ['skill_id' => 18, 'skill_name' => 'Redis', 'category_id' => 3],
            
            // DevOps & Tools
            ['skill_id' => 19, 'skill_name' => 'Docker', 'category_id' => 4],
            ['skill_id' => 20, 'skill_name' => 'Kubernetes', 'category_id' => 4],
            ['skill_id' => 21, 'skill_name' => 'Git', 'category_id' => 4],
            ['skill_id' => 22, 'skill_name' => 'Jenkins', 'category_id' => 4],
            ['skill_id' => 23, 'skill_name' => 'CI/CD', 'category_id' => 4],
            
            // Cloud Platforms
            ['skill_id' => 24, 'skill_name' => 'Azure', 'category_id' => 5],
            ['skill_id' => 25, 'skill_name' => 'AWS', 'category_id' => 5],
            ['skill_id' => 26, 'skill_name' => 'Google Cloud', 'category_id' => 5],
            
            // Soft Skills
            ['skill_id' => 27, 'skill_name' => 'Team Leadership', 'category_id' => 6],
            ['skill_id' => 28, 'skill_name' => 'Communication', 'category_id' => 6],
            ['skill_id' => 29, 'skill_name' => 'Problem Solving', 'category_id' => 6],
            ['skill_id' => 30, 'skill_name' => 'Agile/Scrum', 'category_id' => 6],
        ];

        foreach ($skills as $skill) {
            \Illuminate\Support\Facades\DB::table('skills')->insert([
                'skill_id' => $skill['skill_id'],
                'skill_name' => $skill['skill_name'],
                'category_id' => $skill['category_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
