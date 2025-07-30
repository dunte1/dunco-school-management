<?php

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'data',
        'notifiable_id',
        'notifiable_type',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('notifiable_id', $userId)
                    ->where('notifiable_type', \App\Models\User::class);
    }

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function getIconAttribute()
    {
        switch ($this->type) {
            case 'academic':
                return 'fas fa-graduation-cap';
            case 'finance':
                return 'fas fa-dollar-sign';
            case 'events':
                return 'fas fa-calendar';
            case 'system':
                return 'fas fa-cog';
            case 'communication':
                return 'fas fa-comments';
            default:
                return 'fas fa-bell';
        }
    }

    public function getColorAttribute()
    {
        switch ($this->type) {
            case 'academic':
                return 'primary';
            case 'finance':
                return 'success';
            case 'events':
                return 'info';
            case 'system':
                return 'warning';
            case 'communication':
                return 'secondary';
            default:
                return 'light';
        }
    }
} 