<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;

class GradingPreset extends Model
{
    protected $fillable = [
        'school_id', 'name', 'bands', 'is_active'
    ];

    protected $casts = [
        'school_id' => 'integer',
        'bands' => 'array',
        'is_active' => 'boolean',
    ];
}


