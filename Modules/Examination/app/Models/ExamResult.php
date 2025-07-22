<?php

namespace Modules\Examination\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'exam_id',
        'student_id',
        'exam_attempt_id',
        'total_marks',
        'obtained_marks',
        'percentage',
        'grade',
        'grade_point',
        'result_status',
        'class_position',
        'subject_position',
        'stream_position',
        'remarks',
        'subject_breakdown',
        'is_published',
        'published_at'
    ];

    protected $casts = [
        'total_marks' => 'decimal:2',
        'obtained_marks' => 'decimal:2',
        'percentage' => 'decimal:2',
        'grade_point' => 'decimal:2',
        'subject_breakdown' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'student_id');
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('result_status', $status);
    }

    public function scopePassed($query)
    {
        return $query->where('result_status', 'pass');
    }

    public function scopeFailed($query)
    {
        return $query->where('result_status', 'fail');
    }

    public function getGradeColorAttribute()
    {
        return match($this->result_status) {
            'pass' => 'green',
            'fail' => 'red',
            'absent' => 'gray',
            'disqualified' => 'red',
            default => 'gray'
        };
    }

    public function isPassed(): bool
    {
        return $this->result_status === 'pass';
    }

    public function getRankingTextAttribute()
    {
        $rankings = [];
        
        if ($this->class_position) {
            $rankings[] = "Class: {$this->class_position}";
        }
        
        if ($this->subject_position) {
            $rankings[] = "Subject: {$this->subject_position}";
        }
        
        if ($this->stream_position) {
            $rankings[] = "Stream: {$this->stream_position}";
        }
        
        return implode(', ', $rankings);
    }
} 