<?php

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Broadcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'target_type',
        'target_data',
        'created_by',
        'scheduled_at',
        'sent_at',
        'is_active',
    ];

    protected $casts = [
        'target_data' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function recipients()
    {
        return $this->hasMany(BroadcastRecipient::class);
    }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class, 'attachable_id')->where('attachable_type', Broadcast::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeScheduled($query)
    {
        return $query->whereNotNull('scheduled_at')->whereNull('sent_at');
    }

    public function scopeSent($query)
    {
        return $query->whereNotNull('sent_at');
    }

    public function getTargetDisplayAttribute()
    {
        switch ($this->target_type) {
            case 'all':
                return 'All Users';
            case 'role':
                return 'Specific Roles';
            case 'class':
                return 'Specific Classes';
            case 'group':
                return 'Specific Groups';
            case 'individual':
                return 'Specific Users';
            default:
                return 'Unknown';
        }
    }
} 