<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;

class SkillRubric extends Model
{
    protected $table = 'nursing_skill_rubrics';

    protected $fillable = [
        'name',
        'criteria',
        'description',
        'is_active',
    ];

    protected $casts = [
        'criteria' => 'array',
        'is_active' => 'boolean',
    ];

    public function assessments()
    {
        return $this->hasMany(SkillAssessment::class, 'rubric_id');
    }
}
