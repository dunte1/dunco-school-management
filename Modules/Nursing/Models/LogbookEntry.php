<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogbookEntry extends Model
{
    use SoftDeletes;

    protected $table = 'nursing_logbooks';

    protected $fillable = [
        'school_id',
        'student_id',
        'placement_id',
        'date',
        'shift',
        'hours',
        'activity',
        'procedure',
        'learning_objective',
        'reflection',
        'challenges',
        'evidence',
        'status',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'approved_at',
        'approved_by',
        'review_comments',
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'decimal:1',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
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

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'reviewed_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    public function corrections()
    {
        return $this->hasMany(LogbookCorrection::class);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopePendingReview($query)
    {
        return $query->whereIn('status', ['submitted', 'under_review']);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function isEditable(): bool
    {
        return $this->status === 'draft' || $this->status === 'returned';
    }

    public function canBeSubmitted(): bool
    {
        return $this->status === 'draft' || $this->status === 'returned';
    }

    public function canBeReviewed(): bool
    {
        return in_array($this->status, ['submitted', 'under_review']);
    }
}
