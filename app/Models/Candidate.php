<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    protected $primaryKey = 'candidate_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'candidate_id','full_name','email','phone','location','availability_type','total_experience_years','bio','is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_experience_years' => 'integer',
    ];

    public function skills(): HasMany
    {
        return $this->hasMany(CandidateSkill::class, 'candidate_id', 'candidate_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(CandidateAssignment::class, 'candidate_id', 'candidate_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CandidatePerformanceReview::class, 'candidate_id', 'candidate_id');
    }
}
