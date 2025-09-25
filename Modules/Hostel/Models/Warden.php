<?php

namespace Modules\Hostel\Models;

use Illuminate\Database\Eloquent\Model;

class Warden extends Model
{
    protected $fillable = [
        'user_id',
        'hostel_id',
        'assigned_blocks', // json
        'contact',
        'role', // warden, security_guard
        'status', // active, inactive
    ];

    protected $casts = [
        'assigned_blocks' => 'array',
    ];

    public function hostel() { return $this->belongsTo(Hostel::class); }
    public function user() { return $this->belongsTo(\App\Models\User::class, 'user_id'); }
    public function leaveRequests() { return $this->hasMany(LeaveRequest::class, 'warden_id'); }
    public function announcements() { return $this->hasMany(HostelAnnouncement::class, 'warden_id'); }
} 