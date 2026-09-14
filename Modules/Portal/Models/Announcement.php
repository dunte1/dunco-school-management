<?php

namespace Modules\Portal\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'portal_announcements';

    protected $fillable = ['title', 'message', 'audience', 'is_active', 'published_at', 'expires_at', 'created_by'];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
