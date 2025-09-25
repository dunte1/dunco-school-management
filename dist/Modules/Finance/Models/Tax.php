<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'name',
        'rate',
        'type',
        'description',
        'active',
    ];
} 