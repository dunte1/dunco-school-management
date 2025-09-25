<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\School;
use App\Models\User;

class Student extends Model
{
    protected $table = 'academic_students';
    
    protected $fillable = [
        'school_id',
        'user_id',
        'student_id',
        'name',
        'admission_number',
        'class_id',
        'admission_date',
        'date_of_birth',
        'gender',
        'blood_group',
        'religion',
        'nationality',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'emergency_contact',
        'medical_conditions',
        'disabilities',
        'allergies',
        'previous_school',
        'stream',
        'house',
        'group',
        'is_transfer',
        'enrollment_status',
        'is_active',
        'status',
        'status_reason',
        'status_changed_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
        'is_active' => 'boolean',
        'status_changed_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classes()
    {
        return $this->belongsToMany(AcademicClass::class, 'academic_class_student', 'student_id', 'class_id')
            ->withPivot('enrollment_date', 'is_active')
            ->withTimestamps();
    }

    public function parents()
    {
        return $this->belongsToMany(\App\Models\User::class, 'academic_student_parent', 'student_id', 'parent_id')
            ->withPivot('relationship', 'is_primary')
            ->withTimestamps();
    }

    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]);
        
        return implode(', ', $parts);
    }

    public function getCurrentClassAttribute()
    {
        return $this->classes()
            ->wherePivot('is_active', true)
            ->where('academic_classes.is_active', true)
            ->first();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByEnrollmentStatus($query, $status)
    {
        return $query->where('enrollment_status', $status);
    }

    public function class()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function documents()
    {
        return $this->hasMany(\Modules\Academic\Models\StudentDocument::class, 'student_id');
    }

    public function enrollmentHistory()
    {
        return $this->hasMany(\Modules\Academic\Models\EnrollmentHistory::class, 'student_id');
    }

    public function fees()
    {
        return $this->hasMany(StudentFee::class, 'student_id');
    }
} 