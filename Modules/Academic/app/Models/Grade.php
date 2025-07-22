<?php

namespace Modules\Academic\app\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'name', // e.g., A, B, C
        'min_score',
        'max_score',
        'description',
        'grading_scale_id',
    ];

    public function gradingScale()
    {
        return $this->belongsTo(GradingScale::class);
    }
} 