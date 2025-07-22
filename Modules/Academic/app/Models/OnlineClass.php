<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\School;
use App\Models\User;

class OnlineClass extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'teacher_id',
        'title',
        'description',
        'academic_class_id',
        'subject_id',
        'start_time',
        'end_time',
        'meeting_link',
        'meeting_id',
        'meeting_password',
        'max_participants',
        'is_recording_allowed',
        'instructions',
        'materials',
        'status',
        'started_at',
        'ended_at'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_recording_allowed' => 'boolean',
        'materials' => 'array'
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(OnlineClassAttendance::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now())->where('status', 'scheduled');
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_time', '<=', now())
                    ->where('end_time', '>=', now())
                    ->where('status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('end_time', '<', now())
                    ->where('status', 'completed');
    }

    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('academic_class_id', $classId);
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function isCurrentlyActive(): bool
    {
        $now = now();
        return $this->status === 'ongoing' && 
               $now->between($this->start_time, $this->end_time);
    }

    public function canBeJoined(): bool
    {
        $now = now();
        return $this->status === 'ongoing' && 
               $now->between($this->start_time, $this->end_time);
    }

    public function getDurationAttribute(): int
    {
        return $this->start_time->diffInMinutes($this->end_time);
    }

    public function getParticipantsCountAttribute(): int
    {
        return $this->attendances()->count();
    }

    public function getAttendancePercentageAttribute(): float
    {
        $totalStudents = $this->academicClass->students()->count();
        if ($totalStudents === 0) return 0;
        
        $presentStudents = $this->attendances()->where('status', 'present')->count();
        return round(($presentStudents / $totalStudents) * 100, 2);
    }
} 