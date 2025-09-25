<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\School;

class Subject extends Model
{
    protected $table = 'academic_subjects';
    
    protected $fillable = [
        'school_id',
        'name',
        'code',
        'description',
        'credits',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classes()
    {
        return $this->belongsToMany(AcademicClass::class, 'academic_class_subject', 'subject_id', 'class_id')
            ->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'academic_subject_teacher', 'subject_id', 'teacher_id')
            ->withTimestamps();
    }

    public function prerequisites()
    {
        return $this->belongsToMany(Subject::class, 'subject_prerequisites', 'subject_id', 'prerequisite_subject_id');
    }

    public function groups()
    {
        return $this->belongsToMany(\Modules\Academic\Models\SubjectGroup::class, 'subject_group_subject', 'subject_id', 'group_id');
    }

    public function resources()
    {
        return $this->hasMany(\Modules\Academic\Models\SubjectResource::class, 'subject_id');
    }

    public function feedback()
    {
        return $this->hasMany(\Modules\Academic\Models\SubjectFeedback::class, 'subject_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(\Modules\Academic\Models\SubjectAuditLog::class, 'subject_id');
    }

    public function approvals()
    {
        return $this->hasMany(\Modules\Academic\Models\SubjectApproval::class, 'subject_id');
    }

    public function customFields()
    {
        return $this->hasMany(\Modules\Academic\Models\SubjectCustomField::class, 'subject_id');
    }

    public function capacityLimit()
    {
        return $this->hasOne(\Modules\Academic\Models\SubjectCapacityLimit::class, 'subject_id');
    }

    public function notifications()
    {
        return $this->hasMany(\Modules\Academic\Models\SubjectNotification::class, 'subject_id');
    }

    public function calendarEvents()
    {
        return $this->hasMany(\Modules\Academic\Models\SubjectCalendarEvent::class, 'subject_id');
    }
} 