<?php

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
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
} 