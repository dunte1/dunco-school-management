<?php

namespace Modules\Hostel\Models;

use Illuminate\Database\Eloquent\Model;

class RoomAllocation extends Model
{
    protected $fillable = [
        'bed_id',
        'student_id',
        'allocated_by',
        'allocation_type', // auto/manual/request
        'status', // active, checked_out, swapped, cancelled
        'check_in',
        'check_out',
        'notes',
        'document', // file path for uploaded docs
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function bed() { return $this->belongsTo(Bed::class); }
    public function student() { return $this->belongsTo(\App\Models\User::class, 'student_id'); }
    public function allocatedBy() { return $this->belongsTo(\App\Models\User::class, 'allocated_by'); }
} 