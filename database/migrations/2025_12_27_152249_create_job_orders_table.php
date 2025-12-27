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
        Schema::create('job_orders', function (Blueprint $table) {
            $table->integer('job_id')->primary();
            $table->string('job_title', 100);
            $table->text('job_description')->nullable();
            $table->integer('min_experience_years');
            $table->string('preferred_location', 50)->nullable();
            $table->string('availability_type', 20);
            $table->integer('max_results')->default(10);
            $table->date('posted_date');
            $table->string('status', 20)->default('open');
            $table->integer('recruiter_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_orders');
    }
};
