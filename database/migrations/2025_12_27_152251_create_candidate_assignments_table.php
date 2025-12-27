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
        Schema::create('candidate_assignments', function (Blueprint $table) {
            $table->id('assignment_id');
            $table->integer('candidate_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('job_id');
            $table->string('status', 20)->default('active');
            $table->tinyInteger('rating')->nullable()->checkBetween(1, 5);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('candidate_id')->references('candidate_id')->on('candidates')->onDelete('cascade');
            $table->foreign('job_id')->references('job_id')->on('job_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_assignments');
    }
};
