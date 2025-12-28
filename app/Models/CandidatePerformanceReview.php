<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidatePerformanceReview extends Model
{
    protected $primaryKey = 'review_id';

    protected $fillable = ['candidate_id','assignment_id','rating','completion_status','feedback','review_date'];

    protected $casts = [
        'rating' => 'integer',
        'review_date' => 'datetime',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'candidate_id');
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(CandidateAssignment::class, 'assignment_id', 'assignment_id');
    }
}
