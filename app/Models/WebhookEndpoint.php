<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookEndpoint extends Model
{
    protected $fillable = ['user_id','name','url','secret','events','is_active'];
    protected $casts = [
        'user_id' => 'integer',
        'events' => 'array',
        'is_active' => 'boolean',
    ];
}


