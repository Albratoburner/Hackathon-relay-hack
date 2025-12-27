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
        Schema::create('ranking_history', function (Blueprint $table) {
            $table->id('ranking_id');
            $table->integer('job_id');
            $table->integer('candidate_id');
            $table->integer('rank_position');
            $table->float('total_score');
            $table->float('skill_score')->nullable();
            $table->float('experience_score')->nullable();
            $table->float('availability_score')->nullable();
            $table->float('location_score')->nullable();
            $table->float('cultural_fit_score')->nullable();
            $table->dateTime('execution_date');
            $table->boolean('selected_by_recruiter')->default(false);
            $table->timestamps();
            
            $table->foreign('job_id')->references('job_id')->on('job_orders')->onDelete('cascade');
            $table->foreign('candidate_id')->references('candidate_id')->on('candidates')->onDelete('cascade');
            $table->index(['job_id', 'execution_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranking_history');
    }
};
