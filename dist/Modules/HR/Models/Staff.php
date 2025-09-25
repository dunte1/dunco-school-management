<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'first_name', 'last_name', 'other_names', 'photo', 'email', 'phone', 'gender', 'dob',
        'id_number', 'passport_number', 'address', 'city', 'country', 'job_title', 'role_id',
        'department_id', 'school_id', 'multi_school_ids', 'emergency_contact_name',
        'emergency_contact_phone', 'emergency_contact_relation', 'staff_id', 'qr_code',
        'barcode', 'user_id', 'status'
    ];

    protected $casts = [
        'multi_school_ids' => 'array',
        'dob' => 'date',
    ];

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function documents()
    {
        return $this->hasMany(StaffDocument::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function availabilities()
    {
        return $this->hasMany(\Modules\Timetable\Models\TeacherAvailability::class, 'teacher_id', 'id');
    }
} 