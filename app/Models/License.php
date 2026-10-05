<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = [
        'school_id',
        'plan',
        'status',
        'starts_at',
        'expires_at',
        'seats',
        'features', // json
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'features' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class);
    }
}
