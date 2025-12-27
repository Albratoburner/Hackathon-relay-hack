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
        Schema::create('candidate_performance_reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->integer('candidate_id');
            $table->bigInteger('assignment_id')->unsigned();
            $table->tinyInteger('rating')->checkBetween(1, 5);
            $table->string('completion_status', 20);
            $table->text('feedback')->nullable();
            $table->dateTime('review_date');
            $table->timestamps();
            
            $table->foreign('candidate_id')->references('candidate_id')->on('candidates')->onDelete('no action');
            $table->foreign('assignment_id')->references('assignment_id')->on('candidate_assignments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_performance_reviews');
    }
};
