<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CandidateAssignment extends Model
{
    protected $primaryKey = 'assignment_id';

    protected $fillable = ['candidate_id','start_date','end_date','job_id','status','rating','notes'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'rating' => 'integer',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'candidate_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class, 'job_id', 'job_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CandidatePerformanceReview::class, 'assignment_id', 'assignment_id');
    }
}
