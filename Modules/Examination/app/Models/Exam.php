<?php

namespace Modules\Examination\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'exam_type_id',
        'academic_year',
        'term',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'total_marks',
        'passing_marks',
        'is_online',
        'enable_proctoring',
        'shuffle_questions',
        'shuffle_options',
        'show_results_immediately',
        'allow_review',
        'is_active',
        'status',
        'settings',
        'proctor_webcam',
        'proctor_tab_switch',
        'proctor_face_detection',
        'proctor_idle_timeout',
        'allow_retake',
        'max_attempts',
        'retake_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_marks' => 'decimal:2',
        'passing_marks' => 'decimal:2',
        'is_online' => 'boolean',
        'enable_proctoring' => 'boolean',
        'shuffle_questions' => 'boolean',
        'shuffle_options' => 'boolean',
        'show_results_immediately' => 'boolean',
        'allow_review' => 'boolean',
        'is_active' => 'boolean',
        'settings' => 'array',
        'proctor_webcam' => 'boolean',
        'proctor_tab_switch' => 'boolean',
        'proctor_face_detection' => 'boolean',
        'proctor_idle_timeout' => 'integer',
        'allow_retake' => 'boolean',
        'max_attempts' => 'integer',
        'retake_reason' => 'string',
    ];

    public function examType(): BelongsTo
    {
        return $this->belongsTo(ExamType::class);
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
                    ->withPivot(['order', 'marks', 'is_required', 'settings'])
                    ->withTimestamps()
                    ->orderBy('pivot_order');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ExamSchedule::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnline($query)
    {
        return $query->where('is_online', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('start_date', '>=', now()->toDateString());
    }

    public function isCurrentlyActive(): bool
    {
        $now = now();
        return $this->status === 'ongoing' && 
               $now->between($this->start_date, $this->end_date);
    }

    public function canBeStarted(): bool
    {
        return $this->status === 'published' && 
               now()->gte($this->start_date) && 
               now()->lte($this->end_date);
    }
} 