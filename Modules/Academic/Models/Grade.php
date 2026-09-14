<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'grades';

    protected $fillable = ['name', 'min_score', 'max_score', 'description', 'grading_scale_id'];

    protected $casts = [
        'min_score' => 'integer',
        'max_score' => 'integer',
    ];

    public function gradingScale()
    {
        return $this->belongsTo(GradingScale::class, 'grading_scale_id');
    }
}
