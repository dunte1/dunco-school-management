<?php

namespace Modules\Hostel\Models;

use Illuminate\Database\Eloquent\Model;

class HostelAnnouncement extends Model
{
    protected $fillable = [
        'hostel_id',
        'warden_id',
        'title',
        'message',
        'attachment',
        'audience', // all, residents, staff
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function hostel() { return $this->belongsTo(Hostel::class); }
    public function warden() { return $this->belongsTo(Warden::class); }
} 