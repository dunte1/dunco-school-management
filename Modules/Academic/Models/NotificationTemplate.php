<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $table = 'notification_templates';
    protected $fillable = [
        'event',
        'channel',
        'subject',
        'body',
        'is_active',
    ];
} 