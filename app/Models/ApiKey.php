<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $fillable = ['user_id','name','key','prefix','is_active','last_used_at'];

    protected $casts = [
        'user_id' => 'integer',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];
}


