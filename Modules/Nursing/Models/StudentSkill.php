<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSkill extends Model
{
    protected $table = 'nursing_student_skills';

    protected $fillable = [
        'student_id',
        'skill_id',
        'placement_id',
        'status',
        'awarded_by',
        'awarded_at',
        'notes',
    ];

    protected $casts = [
        'awarded_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(\Modules\Academic\Models\Student::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function placement(): BelongsTo
    {
        return $this->belongsTo(Placement::class);
    }

    public function awardedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'awarded_by');
    }

    public function assessments()
    {
        return $this->hasMany(SkillAssessment::class, 'student_skill_id');
    }

    public function isCompetent(): bool
    {
        return $this->status === 'competent';
    }
}
