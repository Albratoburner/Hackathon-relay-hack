<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruiterPreference extends Model
{
    protected $primaryKey = 'preference_id';

    protected $fillable = ['recruiter_id','job_id','preferred_skill_ids','weight_multiplier'];

    protected $casts = [
        'preferred_skill_ids' => 'array',
        'weight_multiplier' => 'float',
    ];
}
