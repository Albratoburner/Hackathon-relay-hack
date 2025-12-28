<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class JobOrder extends Model
{
    protected $primaryKey = 'job_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'job_id','job_title','job_description','min_experience_years','preferred_location','availability_type','max_results','posted_date','status','recruiter_id'
    ];

    protected $casts = [
        'min_experience_years' => 'integer',
        'max_results' => 'integer',
        'posted_date' => 'date',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(CandidateAssignment::class, 'job_id', 'job_id');
    }

    public function rankings(): HasMany
    {
        return $this->hasMany(RankingHistory::class, 'job_id', 'job_id');
    }
}
