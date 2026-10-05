<?php

namespace Modules\Transport\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleMaintenance extends Model
{
    protected $fillable = [
        'vehicle_id','type','date','cost','odometer','notes'
    ];

    protected $casts = [
        'date' => 'date',
        'cost' => 'decimal:2',
        'odometer' => 'integer',
    ];
}


