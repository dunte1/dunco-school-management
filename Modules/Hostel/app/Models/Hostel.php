<?php

namespace Modules\Hostel\Models;

use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    protected $fillable = [
        'name',
        'location',
        'gender_restriction',
        'school_id',
        'description',
    ];

    public function floors() { return $this->hasMany(Floor::class); }
    public function rooms() { return $this->hasMany(Room::class); }
    public function visitors() { return $this->hasMany(HostelVisitor::class); }
    public function announcements() { return $this->hasMany(HostelAnnouncement::class); }
    public function fees() { return $this->hasMany(HostelFee::class); }
    public function wardens() { return $this->hasMany(Warden::class); }
} 