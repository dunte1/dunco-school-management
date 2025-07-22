<?php

namespace App\Models\Modules\Timetable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timetable extends Model
{
    // Status constants
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        // ... existing fillable fields ...
        'status',
        'submitted_by',
        'approved_by',
        'published_by',
        'archived_by',
        'submitted_at',
        'approved_at',
        'published_at',
        'archived_at',
    ];

    // Relationships
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'submitted_by');
    }
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }
    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'published_by');
    }
    public function archivedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'archived_by');
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }
    public function scopeArchived($query)
    {
        return $query->where('status', self::STATUS_ARCHIVED);
    }
}
