<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Placement extends Model
{
    use SoftDeletes;

    protected $table = 'nursing_placements';

    protected $fillable = [
        'school_id',
        'student_id',
        'facility_id',
        'department_id',
        'ward_id',
        'instructor_id',
        'start_date',
        'end_date',
        'required_hours',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'required_hours' => 'decimal:1',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(\Modules\Academic\Models\Student::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(FacilityDepartment::class, 'department_id');
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(\Modules\HR\Models\Staff::class, 'instructor_id');
    }

    public function logbooks(): HasMany
    {
        return $this->hasMany(LogbookEntry::class);
    }

    public function clinicalHours(): HasMany
    {
        return $this->hasMany(ClinicalHours::class);
    }

    public function studentSkills(): HasMany
    {
        return $this->hasMany(StudentSkill::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForInstructor($query, $staffId)
    {
        return $query->where('instructor_id', $staffId);
    }

    public function getCompletedHoursAttribute(): float
    {
        return $this->clinicalHours()->where('status', 'approved')->sum('hours');
    }

    public function getRemainingHoursAttribute(): float
    {
        return max(0, $this->required_hours - $this->completed_hours);
    }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->required_hours <= 0) return 0;
        return min(100, round(($this->completed_hours / $this->required_hours) * 100, 1));
    }
}
