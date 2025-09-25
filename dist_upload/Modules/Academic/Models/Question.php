<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\School;

class Question extends Model
{
    protected $table = 'academic_questions';
    
    protected $fillable = [
        'school_id',
        'subject_id',
        'question_text',
        'question_type', // multiple_choice, true_false, short_answer, essay
        'difficulty_level', // easy, medium, hard
        'marks',
        'options', // JSON for multiple choice
        'correct_answer',
        'explanation',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'academic_exam_question', 'question_id', 'exam_id')
            ->withPivot('order', 'marks')
            ->withTimestamps();
    }

    public function getDifficultyColorAttribute()
    {
        return match($this->difficulty_level) {
            'easy' => 'success',
            'medium' => 'warning',
            'hard' => 'danger',
            default => 'secondary'
        };
    }

    public function getQuestionTypeLabelAttribute()
    {
        return match($this->question_type) {
            'multiple_choice' => 'Multiple Choice',
            'true_false' => 'True/False',
            'short_answer' => 'Short Answer',
            'essay' => 'Essay',
            default => 'Unknown'
        };
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('question_type', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
} 