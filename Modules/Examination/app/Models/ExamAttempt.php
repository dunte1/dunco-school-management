<?php

namespace Modules\Examination\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamAttempt extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'exam_id',
        'student_id',
        'attempt_code',
        'started_at',
        'submitted_at',
        'expires_at',
        'status',
        'total_marks',
        'obtained_marks',
        'time_taken_minutes',
        'proctoring_data',
        'device_info',
        'notes',
        'is_graded'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'expires_at' => 'datetime',
        'total_marks' => 'decimal:2',
        'obtained_marks' => 'decimal:2',
        'proctoring_data' => 'array',
        'device_info' => 'array',
        'is_graded' => 'boolean'
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'student_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class);
    }

    public function proctoringLogs(): HasMany
    {
        return $this->hasMany(ProctoringLog::class);
    }

    public function result(): HasMany
    {
        return $this->hasOne(ExamResult::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['started', 'in_progress']);
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['submitted', 'timeout']);
    }

    public function getPercentageAttribute()
    {
        if ($this->total_marks > 0) {
            return round(($this->obtained_marks / $this->total_marks) * 100, 2);
        }
        return 0;
    }

    public function getTimeRemainingAttribute()
    {
        if ($this->expires_at && $this->status === 'in_progress') {
            $remaining = now()->diffInSeconds($this->expires_at, false);
            return max(0, $remaining);
        }
        return 0;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && now()->gt($this->expires_at);
    }

    public function canSubmit(): bool
    {
        return in_array($this->status, ['started', 'in_progress']) && !$this->isExpired();
    }

    public function getSuspiciousActivityCountAttribute()
    {
        return $this->proctoringLogs()
                    ->whereIn('severity', ['high', 'critical'])
                    ->count();
    }
} 