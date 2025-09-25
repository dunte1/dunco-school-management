<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class StaffAttendanceRecord extends Model
{
    protected $table = 'staff_attendance_records';

    protected $fillable = [
        'school_id',
        'staff_id',
        'date',
        'status', // present, absent, late, excused, sick, on_leave, suspended
        'remarks',
        'marked_by',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function scopeByStaff($query, $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
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
            'sick' => 'success',
            'on_leave' => 'primary',
            'suspended' => 'secondary',
            default => 'secondary'
        };
    }
} 