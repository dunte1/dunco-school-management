<?php

namespace Modules\Hostel\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'hostel_id',
        'floor_id',
        'name',
        'type', // single, double, triple
        'ac', // boolean
        'ensuite', // boolean
        'capacity',
        'amenities', // json
        'layout_image',
        'price_per_bed',
        'status', // available, occupied, maintenance, reserved
        'description',
    ];

    protected $casts = [
        'ac' => 'boolean',
        'ensuite' => 'boolean',
        'amenities' => 'array',
        'price_per_bed' => 'decimal:2',
    ];

    public function hostel() { return $this->belongsTo(Hostel::class); }
    public function floor() { return $this->belongsTo(Floor::class); }
    public function beds() { return $this->hasMany(Bed::class); }
    public function issues() { return $this->hasMany(HostelIssue::class); }
    public function allocations() { return $this->hasManyThrough(RoomAllocation::class, Bed::class, 'room_id', 'bed_id', 'id', 'id'); }
    public function fees() { return $this->hasMany(HostelFee::class); }
} 