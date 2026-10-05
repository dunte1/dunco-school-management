<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSchedule extends Model
{
    protected $fillable = [
        'school_id', 'template_id', 'channel', 'audience', 'cron', 'is_active', 'last_run_at'
    ];

    protected $casts = [
        'audience' => 'array',
        'is_active' => 'boolean',
    ];

    public function template()
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }
}





