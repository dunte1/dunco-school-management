<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamAttempt extends Model
{
    use SoftDeletes;

    protected $table = 'exam_attempts';

    protected $fillable = [
        'exam_id', 'student_id', 'attempt_code', 'started_at', 'submitted_at',
        'expires_at', 'status', 'total_marks', 'obtained_marks',
        'time_taken_minutes', 'proctoring_data', 'device_info', 'notes', 'is_graded',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'expires_at' => 'datetime',
        'total_marks' => 'decimal:2',
        'obtained_marks' => 'decimal:2',
        'proctoring_data' => 'array',
        'device_info' => 'array',
        'is_graded' => 'boolean',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function student()
    {
        return $this->belongsTo(\App\Models\User::class, 'student_id');
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class, 'exam_attempt_id');
    }

    public function proctoringLogs()
    {
        return $this->hasMany(ProctoringLog::class, 'exam_attempt_id');
    }

    public function result()
    {
        return $this->hasOne(ExamResult::class, 'exam_attempt_id');
    }
}
