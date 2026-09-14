<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $table = 'exams';

    protected $fillable = [
        'name', 'code', 'description', 'exam_type_id', 'academic_year', 'term',
        'start_date', 'end_date', 'start_time', 'end_time', 'duration_minutes',
        'total_marks', 'passing_marks', 'is_online', 'enable_proctoring',
        'shuffle_questions', 'shuffle_options', 'show_results_immediately',
        'allow_review', 'is_active', 'status', 'settings', 'negative_marking',
        'proctor_webcam', 'proctor_tab_switch', 'proctor_face_detection',
        'proctor_idle_timeout', 'allow_retake', 'max_attempts', 'retake_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'settings' => 'array',
        'total_marks' => 'decimal:2',
        'passing_marks' => 'decimal:2',
        'negative_marking' => 'decimal:2',
        'is_online' => 'boolean',
        'enable_proctoring' => 'boolean',
        'shuffle_questions' => 'boolean',
        'shuffle_options' => 'boolean',
        'show_results_immediately' => 'boolean',
        'allow_review' => 'boolean',
        'is_active' => 'boolean',
        'proctor_webcam' => 'boolean',
        'proctor_tab_switch' => 'boolean',
        'proctor_face_detection' => 'boolean',
        'allow_retake' => 'boolean',
    ];

    public function type()
    {
        return $this->belongsTo(ExamType::class, 'exam_type_id');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
            ->withPivot(['order', 'marks', 'is_required', 'settings'])
            ->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(ExamSchedule::class, 'exam_id');
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class, 'exam_id');
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class, 'exam_id');
    }
}
