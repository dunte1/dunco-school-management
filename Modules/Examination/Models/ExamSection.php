<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id', 'name', 'description', 'total_marks', 'order', 'is_optional'
    ];

    protected $casts = [
        'is_optional' => 'boolean',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class);
    }
}