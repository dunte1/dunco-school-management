<?php

namespace Modules\Examination\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamAnswer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'exam_attempt_id',
        'question_id',
        'student_answer',
        'essay_answer',
        'code_answer',
        'file_path',
        'marks_obtained',
        'max_marks',
        'is_correct',
        'feedback',
        'auto_grade_data',
        'is_graded',
        'answered_at',
        'time_spent_seconds'
    ];

    protected $casts = [
        'student_answer' => 'array',
        'marks_obtained' => 'decimal:2',
        'max_marks' => 'decimal:2',
        'is_correct' => 'boolean',
        'auto_grade_data' => 'array',
        'is_graded' => 'boolean',
        'answered_at' => 'datetime'
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function scopeGraded($query)
    {
        return $query->where('is_graded', true);
    }

    public function scopeUngraded($query)
    {
        return $query->where('is_graded', false);
    }

    public function scopeByQuestion($query, $questionId)
    {
        return $query->where('question_id', $questionId);
    }

    public function getAnswerTextAttribute()
    {
        if ($this->question->type === 'essay') {
            return $this->essay_answer;
        }
        
        if ($this->question->type === 'coding') {
            return $this->code_answer;
        }
        
        if (is_array($this->student_answer)) {
            return implode(', ', $this->student_answer);
        }
        
        return $this->student_answer;
    }

    public function getIsCorrectAttribute($value)
    {
        if ($value !== null) {
            return $value;
        }
        
        // Auto-grade if not already graded
        if (!$this->is_graded && $this->question->type !== 'essay') {
            $isCorrect = $this->question->isCorrect($this->student_answer);
            $this->update([
                'is_correct' => $isCorrect,
                'marks_obtained' => $isCorrect ? $this->max_marks : 0,
                'is_graded' => true
            ]);
            return $isCorrect;
        }
        
        return $value;
    }
} 