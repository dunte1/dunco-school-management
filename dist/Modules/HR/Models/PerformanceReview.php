<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    protected $fillable = [
        'staff_id',
        'reviewer_id',
        'period',
        'score',
        'comments',
        'review_date',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Staff::class, 'reviewer_id');
    }
} 