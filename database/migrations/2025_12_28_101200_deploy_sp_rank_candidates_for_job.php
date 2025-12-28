<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $path = base_path('sql/sp_RankCandidatesForJob.sql');
        if (File::exists($path)) {
            $sql = File::get($path);
            
            // Split by GO and execute each batch
            $batches = array_filter(array_map('trim', preg_split('/\bGO\b/i', $sql)));
            
            foreach ($batches as $batch) {
                if (!empty($batch)) {
                    DB::unprepared($batch);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_RankCandidatesForJob;');
    }
};
