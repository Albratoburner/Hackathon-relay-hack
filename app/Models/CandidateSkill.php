<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateSkill extends Model
{
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = ['candidate_id','skill_id','proficiency_level','level_id','years_of_experience'];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'candidate_id');
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id', 'skill_id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(SkillLevel::class, 'level_id', 'level_id');
    }
}
