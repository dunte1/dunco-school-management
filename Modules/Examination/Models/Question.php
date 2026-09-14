<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $table = 'questions';

    protected $fillable = [
        'question_text', 'type', 'category_id', 'options', 'correct_answers',
        'explanation', 'marks', 'time_limit_seconds', 'metadata', 'is_active',
        'difficulty', 'tags', 'feedback', 'file_upload',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
        'metadata' => 'array',
        'tags' => 'array',
        'marks' => 'decimal:2',
        'is_active' => 'boolean',
        'file_upload' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(QuestionCategory::class, 'category_id');
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_questions')
            ->withPivot(['order', 'marks', 'is_required', 'settings'])
            ->withTimestamps();
    }
}
