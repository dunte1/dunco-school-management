<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAnswer extends Model
{
    protected $table = 'exam_answers';

    protected $fillable = [
        'exam_attempt_id', 'question_id', 'student_answer', 'essay_answer',
        'code_answer', 'file_path', 'marks_obtained', 'max_marks', 'is_correct',
        'feedback', 'auto_grade_data', 'is_graded', 'answered_at', 'time_spent_seconds',
    ];

    protected $casts = [
        'student_answer' => 'array',
        'auto_grade_data' => 'array',
        'marks_obtained' => 'decimal:2',
        'max_marks' => 'decimal:2',
        'is_correct' => 'boolean',
        'is_graded' => 'boolean',
        'answered_at' => 'datetime',
    ];

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
