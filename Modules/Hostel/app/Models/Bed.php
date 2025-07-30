<?php

namespace Modules\Hostel\Models;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    protected $fillable = [
        'room_id',
        'bed_number',
        'status', // available, occupied, maintenance, reserved
        'student_id', // nullable, assigned student
        'price',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function room() { return $this->belongsTo(Room::class); }
    public function allocations() { return $this->hasMany(RoomAllocation::class); }
    public function issues() { return $this->hasMany(HostelIssue::class); }
    public function fees() { return $this->hasMany(HostelFee::class); }
} 