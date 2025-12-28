<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Index for candidate_skills lookups by skill_id
        Schema::table('candidate_skills', function (Blueprint $table) {
            $table->index('skill_id', 'idx_candidate_skills_skill_id');
        });

        // Composite index for candidate_assignments lookups
        Schema::table('candidate_assignments', function (Blueprint $table) {
            $table->index(['candidate_id', 'job_id'], 'idx_candidate_assignments_candidate_job');
        });

        // Composite index for ranking_history queries
        Schema::table('ranking_history', function (Blueprint $table) {
            $table->index(['job_id', 'execution_date'], 'idx_ranking_history_job_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_skills', function (Blueprint $table) {
            $table->dropIndex('idx_candidate_skills_skill_id');
        });

        Schema::table('candidate_assignments', function (Blueprint $table) {
            $table->dropIndex('idx_candidate_assignments_candidate_job');
        });

        Schema::table('ranking_history', function (Blueprint $table) {
            $table->dropIndex('idx_ranking_history_job_date');
        });
    }
};
