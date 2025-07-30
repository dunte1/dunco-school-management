<?php

namespace Modules\Hostel\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'student_id',
        'warden_id',
        'reason',
        'from_date',
        'to_date',
        'status', // pending, approved, rejected, cancelled
        'emergency_contact',
        'guardian_notified',
        'notes',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'guardian_notified' => 'boolean',
    ];

    public function student() { return $this->belongsTo(\App\Models\User::class, 'student_id'); }
    public function warden() { return $this->belongsTo(\App\Models\User::class, 'warden_id'); }
} 