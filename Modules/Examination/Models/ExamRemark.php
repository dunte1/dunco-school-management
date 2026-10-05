<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;

class ExamRemark extends Model
{
    protected $fillable = [
        'exam_id', 'student_id', 'requested_by', 'assigned_to', 'status', 'remark', 'resolution'
    ];

    protected $casts = [
        'exam_id' => 'integer',
        'student_id' => 'integer',
        'requested_by' => 'integer',
        'assigned_to' => 'integer',
    ];
}


