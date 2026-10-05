<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branding extends Model
{
    protected $fillable = [
        'school_id','name','primary_color','secondary_color','text_color','logo_path','auth_bg_path','hero_bg_path','favicon_path'
    ];
}


