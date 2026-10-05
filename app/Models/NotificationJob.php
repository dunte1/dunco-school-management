<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationJob extends Model
{
    protected $fillable = [
        'template_id',
        'channel',
        'recipient',
        'payload',
        'scheduled_at',
        'status',
        'attempts',
        'last_error',
        'next_run_at',
        'dedup_key',
    ];

    protected $casts = [
        'payload' => 'array',
        'scheduled_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }
}
 
