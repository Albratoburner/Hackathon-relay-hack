<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    protected $primaryKey = 'skill_id';
    protected $keyType = 'int';

    protected $fillable = ['skill_id','skill_name','category_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SkillCategory::class, 'category_id', 'category_id');
    }

    public function candidateSkills(): HasMany
    {
        return $this->hasMany(CandidateSkill::class, 'skill_id', 'skill_id');
    }
}
