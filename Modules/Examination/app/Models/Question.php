<?php

namespace Modules\Examination\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'question_text',
        'type',
        'category_id',
        'options',
        'correct_answers',
        'explanation',
        'marks',
        'time_limit_seconds',
        'metadata',
        'is_active',
        'difficulty',
        'tags',
        'feedback',
        'file_upload'
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
        'marks' => 'decimal:2',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'tags' => 'array',
        'file_upload' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(QuestionCategory::class, 'category_id');
    }

    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class, 'exam_questions')
                    ->withPivot(['order', 'marks', 'is_required', 'settings'])
                    ->withTimestamps();
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->whereHas('category', function ($q) use ($difficulty) {
            $q->where('difficulty', $difficulty);
        });
    }

    public function isCorrect($answer): bool
    {
        if ($this->type === 'mcq') {
            return in_array($answer, $this->correct_answers ?? []);
        }
        
        if ($this->type === 'true_false') {
            return $answer === ($this->correct_answers[0] ?? null);
        }
        
        if ($this->type === 'fill_blank') {
            $correctAnswers = array_map('strtolower', $this->correct_answers ?? []);
            return in_array(strtolower(trim($answer)), $correctAnswers);
        }
        
        return false;
    }

    public function getShuffledOptionsAttribute()
    {
        if ($this->type === 'mcq' && $this->options) {
            $options = $this->options;
            shuffle($options);
            return $options;
        }
        
        return $this->options;
    }

    public function getQuestionTypeLabelAttribute()
    {
        return match($this->type) {
            'mcq' => 'Multiple Choice',
            'fill_blank' => 'Fill in the Blank',
            'essay' => 'Essay',
            'coding' => 'Coding',
            'matching' => 'Matching',
            'true_false' => 'True/False',
            'short_answer' => 'Short Answer',
            default => ucfirst($this->type)
        };
    }
} 