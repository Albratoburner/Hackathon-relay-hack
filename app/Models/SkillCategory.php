<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class SkillCategory extends Model
{
    protected $primaryKey = 'category_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['category_id','category_name'];

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class, 'category_id', 'category_id');
    }
}
