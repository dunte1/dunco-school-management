<?php

namespace Modules\Hostel\Models;

use Illuminate\Database\Eloquent\Model;

class HostelIssue extends Model
{
    protected $fillable = [
        'room_id',
        'bed_id',
        'student_id',
        'reported_by',
        'assigned_to',
        'issue_type',
        'description',
        'priority', // low, medium, high
        'status', // open, in_progress, resolved, closed
        'resolved_at',
        'resolution_notes',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function room() { return $this->belongsTo(Room::class); }
    public function bed() { return $this->belongsTo(Bed::class); }
    public function student() { return $this->belongsTo(\App\Models\User::class, 'student_id'); }
    public function reportedBy() { return $this->belongsTo(\App\Models\User::class, 'reported_by'); }
    public function assignedTo() { return $this->belongsTo(\App\Models\User::class, 'assigned_to'); }
} 