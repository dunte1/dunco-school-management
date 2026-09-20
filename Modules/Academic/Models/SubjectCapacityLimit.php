<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectCapacityLimit extends Model
{
    protected $table = 'subject_capacity_limits';

    protected $fillable = [
        'subject_id',
        'academic_class_id',
        'max_students',
        'current_count',
    ];

    protected $casts = [
        'max_students' => 'integer',
        'current_count' => 'integer',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'academic_class_id');
    }

    public function isFull()
    {
        return $this->current_count >= $this->max_students;
    }

    public function availableSlots()
    {
        return max(0, $this->max_students - $this->current_count);
    }
}
