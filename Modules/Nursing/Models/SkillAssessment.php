<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillAssessment extends Model
{
    protected $table = 'nursing_skill_assessments';

    protected $fillable = [
        'student_id',
        'skill_id',
        'student_skill_id',
        'assessor_id',
        'rubric_id',
        'criteria_scores',
        'score',
        'maximum_score',
        'percentage',
        'result',
        'feedback',
        'recommendation',
        'assessed_at',
    ];

    protected $casts = [
        'criteria_scores' => 'array',
        'score' => 'decimal:2',
        'maximum_score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'assessed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(\Modules\Academic\Models\Student::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function studentSkill(): BelongsTo
    {
        return $this->belongsTo(StudentSkill::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'assessor_id');
    }

    public function rubric(): BelongsTo
    {
        return $this->belongsTo(SkillRubric::class, 'rubric_id');
    }
}
