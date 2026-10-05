<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDeviceToken extends Model
{
    protected $fillable = [
        'user_id','platform','token','device_id','app_version','last_seen_at'
    ];
}


