<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    protected $table = 'exam_schedules';

    protected $fillable = [
        'exam_id', 'class_name', 'section', 'subject', 'stream', 'exam_date',
        'start_time', 'end_time', 'room_number', 'max_students', 'invigilators',
        'instructions', 'is_active',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'invigilators' => 'array',
        'is_active' => 'boolean',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
