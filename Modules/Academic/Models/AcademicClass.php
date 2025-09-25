<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\School;
use App\Models\User;

class AcademicClass extends Model
{
    protected $table = 'academic_classes';
    
    protected $fillable = [
        'school_id',
        'name',
        'code',
        'description',
        'capacity',
        'teacher_id',
        'academic_year',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'academic_class_student', 'class_id', 'student_id')
            ->withTimestamps();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'academic_class_subject', 'class_id', 'subject_id')
            ->withTimestamps();
    }
} 