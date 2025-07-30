<?php

namespace Modules\Hostel\Models;

use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    protected $fillable = [
        'hostel_id',
        'name',
        'block',
        'description',
    ];

    public function hostel() { return $this->belongsTo(Hostel::class); }
    public function rooms() { return $this->hasMany(Room::class); }
} 