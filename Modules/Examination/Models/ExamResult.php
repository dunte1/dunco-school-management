<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $table = 'exam_results';

    protected $fillable = [
        'exam_id', 'student_id', 'exam_attempt_id', 'total_marks', 'obtained_marks',
        'percentage', 'grade', 'grade_point', 'result_status', 'class_position',
        'subject_position', 'stream_position', 'remarks', 'subject_breakdown',
        'is_published', 'published_at',
    ];

    protected $casts = [
        'total_marks' => 'decimal:2',
        'obtained_marks' => 'decimal:2',
        'percentage' => 'decimal:2',
        'subject_breakdown' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function student()
    {
        return $this->belongsTo(\App\Models\User::class, 'student_id');
    }

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }
}
