<?php

namespace Modules\Portal\app\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements';

    protected $fillable = [
        'title',
        'message',
        'audience', // e.g., 'all', 'students', 'parents', 'staff'
        'published_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];
} 