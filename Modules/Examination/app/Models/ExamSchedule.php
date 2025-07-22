<?php

namespace Modules\Examination\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSchedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'exam_id',
        'class_name',
        'section',
        'subject',
        'stream',
        'exam_date',
        'start_time',
        'end_time',
        'room_number',
        'max_students',
        'invigilators',
        'instructions',
        'is_active'
    ];

    protected $casts = [
        'exam_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'invigilators' => 'array',
        'is_active' => 'boolean'
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByClass($query, $className)
    {
        return $query->where('class_name', $className);
    }

    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('exam_date', $date);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('exam_date', '>=', now()->toDateString());
    }

    public function getDurationAttribute()
    {
        if ($this->start_time && $this->end_time) {
            return $this->start_time->diffInMinutes($this->end_time);
        }
        return null;
    }

    public function isCurrentlyActive(): bool
    {
        $now = now();
        return $this->exam_date->isToday() && 
               $now->between($this->start_time, $this->end_time);
    }

    public function canBeStarted(): bool
    {
        $now = now();
        return $this->exam_date->isToday() && 
               $now->gte($this->start_time) && 
               $now->lte($this->end_time);
    }
} 