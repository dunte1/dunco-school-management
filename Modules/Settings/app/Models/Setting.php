<?php

namespace Modules\Settings\App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'school_id',
        'key',
        'value',
        'type',
        'description',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class, 'school_id');
    }
} 