<?php

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserNotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'channel',
        'category',
        'enabled',
        'sound',
        'vibration',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'sound' => 'boolean',
        'vibration' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function getChannelDisplayAttribute()
    {
        switch ($this->channel) {
            case 'email':
                return 'Email';
            case 'sms':
                return 'SMS';
            case 'push':
                return 'Push Notification';
            case 'in_app':
                return 'In-App';
            default:
                return ucfirst($this->channel);
        }
    }

    public function getCategoryDisplayAttribute()
    {
        switch ($this->category) {
            case 'academic':
                return 'Academic';
            case 'finance':
                return 'Finance';
            case 'events':
                return 'Events';
            case 'system':
                return 'System';
            case 'communication':
                return 'Communication';
            default:
                return ucfirst($this->category);
        }
    }
} 