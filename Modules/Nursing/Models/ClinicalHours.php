<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalHours extends Model
{
    protected $table = 'nursing_clinical_hours';

    protected $fillable = [
        'school_id',
        'student_id',
        'placement_id',
        'date',
        'hours',
        'shift',
        'status',
        'approved_by',
        'approved_at',
        'notes',
        'logbook_id',
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'decimal:1',
        'approved_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(\Modules\Academic\Models\Student::class);
    }

    public function placement(): BelongsTo
    {
        return $this->belongsTo(Placement::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    public function logbook(): BelongsTo
    {
        return $this->belongsTo(LogbookEntry::class, 'logbook_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForPlacement($query, $placementId)
    {
        return $query->where('placement_id', $placementId);
    }
}
