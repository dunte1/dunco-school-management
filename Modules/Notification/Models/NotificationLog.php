<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationLog extends Model
{
    use HasFactory;

    protected $table = 'notification_logs';

    protected $fillable = [
        'template_id',
        'recipient_id',
        'recipient_email',
        'recipient_phone',
        'channel',
        'subject',
        'body',
        'status',
        'error_message',
        'sent_at',
        'read_at',
        'metadata',
        'school_id',
    ];

    protected $casts = [
        'metadata' => 'array',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }

    public function recipient()
    {
        return $this->belongsTo(\App\Models\User::class, 'recipient_id');
    }

    public function school()
    {
        return $this->belongsTo(\Modules\Core\Models\School::class, 'school_id');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOfChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }
}
