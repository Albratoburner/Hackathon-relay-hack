<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in dependency order
        $this->call([
            SkillCategorySeeder::class,
            SkillLevelSeeder::class,
            SkillSeeder::class,
            CandidateSeeder::class,
            CandidateSkillSeeder::class,
            JobOrderSeeder::class,
            CandidateAssignmentSeeder::class,
            CandidatePerformanceReviewSeeder::class,
        ]);
        
        $this->command->info('✅ All seeders completed successfully!');
        $this->command->info('📊 Database populated with:');
        $this->command->info('   - 6 skill categories');
        $this->command->info('   - 5 skill levels');
        $this->command->info('   - 30 skills');
        $this->command->info('   - 60 candidates');
        $this->command->info('   - 200+ candidate-skill mappings');
        $this->command->info('   - 10 job orders');
        $this->command->info('   - 100+ assignment records');
        $this->command->info('   - 80+ performance reviews');
    }
}
