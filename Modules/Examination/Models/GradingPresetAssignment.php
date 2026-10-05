<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;

class GradingPresetAssignment extends Model
{
    protected $fillable = [
        'grading_preset_id', 'class_id', 'exam_id'
    ];

    protected $casts = [
        'grading_preset_id' => 'integer',
        'class_id' => 'integer',
        'exam_id' => 'integer',
    ];
}


