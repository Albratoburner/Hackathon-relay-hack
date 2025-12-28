<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankingHistory extends Model
{
    protected $table = 'ranking_history';
    protected $primaryKey = 'ranking_id';

    protected $fillable = [
        'job_id','candidate_id','rank_position','total_score','skill_score','experience_score','availability_score','location_score','cultural_fit_score','execution_date','selected_by_recruiter'
    ];

    protected $casts = [
        'execution_date' => 'datetime',
        'selected_by_recruiter' => 'boolean',
    ];
}
