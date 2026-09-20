<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $table = 'notification_templates';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'channel',
        'subject',
        'body',
        'variables',
        'is_active',
        'school_id',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(\Modules\Core\Models\School::class, 'school_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOfChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }

    /**
     * Render the template body with the given variables.
     */
    public function render(array $params = []): string
    {
        $body = $this->body;
        foreach ($params as $key => $value) {
            $body = str_replace('{' . $key . '}', $value, $body);
        }
        return $body;
    }

    /**
     * Render the subject with the given variables.
     */
    public function renderSubject(array $params = []): string
    {
        $subject = $this->subject;
        foreach ($params as $key => $value) {
            $subject = str_replace('{' . $key . '}', $value, $subject);
        }
        return $subject;
    }
}
