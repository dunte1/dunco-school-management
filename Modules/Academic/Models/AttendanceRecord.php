<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\School;

class AttendanceRecord extends Model
{
    protected $table = 'academic_attendance_records';
    
    protected $fillable = [
        'school_id',
        'student_id',
        'class_id',
        'date',
        'status', // present, absent, late, excused
        'remarks',
        'marked_by',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function class()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function markedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'marked_by');
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'present' => 'success',
            'absent' => 'danger',
            'late' => 'warning',
            'excused' => 'info',
            default => 'secondary'
        };
    }
} 