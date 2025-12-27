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
        Schema::create('recruiter_preferences', function (Blueprint $table) {
            $table->id('preference_id');
            $table->integer('recruiter_id');
            $table->integer('job_id');
            $table->text('preferred_skill_ids')->nullable();
            $table->float('weight_multiplier')->default(1.0);
            $table->timestamps();
            
            $table->foreign('job_id')->references('job_id')->on('job_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruiter_preferences');
    }
};
