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
        Schema::create('candidate_skills', function (Blueprint $table) {
            $table->integer('candidate_id');
            $table->integer('skill_id');
            $table->tinyInteger('proficiency_level')->checkBetween(1, 5);
            $table->integer('level_id');
            $table->integer('years_of_experience')->default(0);
            $table->timestamps();
            
            $table->primary(['candidate_id', 'skill_id']);
            $table->foreign('candidate_id')->references('candidate_id')->on('candidates')->onDelete('cascade');
            $table->foreign('skill_id')->references('skill_id')->on('skills')->onDelete('cascade');
            $table->foreign('level_id')->references('level_id')->on('skill_levels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_skills');
    }
};
