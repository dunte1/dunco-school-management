<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionGrading extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_attempt_id', 'question_id', 'graded_by', 'grading_rubric_id',
        'marks_obtained', 'total_marks', 'rubric_scores', 'feedback',
        'comments', 'status', 'graded_at', 'moderated_at'
    ];

    protected $casts = [
        'rubric_scores' => 'array',
        'graded_at' => 'datetime',
        'moderated_at' => 'datetime',
    ];

    public function examAttempt()
    {
        return $this->belongsTo(ExamAttempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function gradingRubric()
    {
        return $this->belongsTo(GradingRubric::class);
    }

    public function disputes()
    {
        return $this->hasMany(GradeDispute::class);
    }

    public function getPercentageAttribute()
    {
        return $this->total_marks > 0 ? round(($this->marks_obtained / $this->total_marks) * 100, 2) : 0;
    }

    public function getGradeAttribute()
    {
        if ($this->gradingRubric) {
            return $this->gradingRubric->getGradeFromScore($this->marks_obtained);
        }
        return null;
    }

    public function isDisputed()
    {
        return $this->disputes()->where('status', '!=', 'resolved')->exists();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeGraded($query)
    {
        return $query->where('status', 'graded');
    }

    public function scopeModerated($query)
    {
        return $query->where('status', 'moderated');
    }

    public function scopeDisputed($query)
    {
        return $query->where('status', 'disputed');
    }
}
