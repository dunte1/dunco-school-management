<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentHistory extends Model
{
    protected $table = 'academic_enrollment_history';
    protected $fillable = [
        'student_id',
        'class_id',
        'academic_year',
        'status',
        'changed_at',
    ];
    public $timestamps = true;

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function class()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }
} 