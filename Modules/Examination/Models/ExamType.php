<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    protected $table = 'exam_types';

    protected $fillable = ['name', 'code', 'description', 'is_online', 'is_active'];

    protected $casts = [
        'is_online' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function exams()
    {
        return $this->hasMany(Exam::class, 'exam_type_id');
    }
}
