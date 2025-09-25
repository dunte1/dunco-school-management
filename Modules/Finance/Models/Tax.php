<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'taxes';
    
    protected $fillable = [
        'name',
        'rate',
        'type',
        'active',
    ];
} 