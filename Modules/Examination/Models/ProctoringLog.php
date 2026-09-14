<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;

class ProctoringLog extends Model
{
    protected $table = 'proctoring_logs';

    protected $fillable = [
        'exam_attempt_id', 'event_type', 'description', 'event_data', 'severity',
        'is_resolved', 'resolution_notes', 'resolved_by', 'resolved_at',
    ];

    protected $casts = [
        'event_data' => 'array',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }

    public function resolver()
    {
        return $this->belongsTo(\App\Models\User::class, 'resolved_by');
    }
}
