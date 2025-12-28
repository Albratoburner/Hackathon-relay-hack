<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_id' => 1, 'category_name' => 'Programming Languages'],
            ['category_id' => 2, 'category_name' => 'Frameworks & Libraries'],
            ['category_id' => 3, 'category_name' => 'Databases'],
            ['category_id' => 4, 'category_name' => 'DevOps & Tools'],
            ['category_id' => 5, 'category_name' => 'Cloud Platforms'],
            ['category_id' => 6, 'category_name' => 'Soft Skills'],
        ];

        foreach ($categories as $category) {
            DB::table('skill_categories')->insert([
                'category_id' => $category['category_id'],
                'category_name' => $category['category_name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
