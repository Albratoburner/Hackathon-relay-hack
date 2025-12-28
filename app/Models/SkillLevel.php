<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class SkillLevel extends Model
{
    protected $primaryKey = 'level_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['level_id','level_name','multiplier'];

    protected $casts = [
        'multiplier' => 'float',
    ];

    public function candidateSkills(): HasMany
    {
        return $this->hasMany(CandidateSkill::class, 'level_id', 'level_id');
    }
}
