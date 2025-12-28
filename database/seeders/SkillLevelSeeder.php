<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['level_id' => 1, 'level_name' => 'Beginner', 'multiplier' => 0.5],
            ['level_id' => 2, 'level_name' => 'Intermediate', 'multiplier' => 1.0],
            ['level_id' => 3, 'level_name' => 'Advanced', 'multiplier' => 1.5],
            ['level_id' => 4, 'level_name' => 'Expert', 'multiplier' => 2.0],
            ['level_id' => 5, 'level_name' => 'Master', 'multiplier' => 2.5],
        ];

        foreach ($levels as $level) {
            \Illuminate\Support\Facades\DB::table('skill_levels')->insert([
                'level_id' => $level['level_id'],
                'level_name' => $level['level_name'],
                'multiplier' => $level['multiplier'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
