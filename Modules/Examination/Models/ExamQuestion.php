<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id', 'exam_section_id', 'question_id', 'order', 'marks'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function examSection()
    {
        return $this->belongsTo(ExamSection::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
