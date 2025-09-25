<?php

namespace Modules\Transport\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'stop_name',
        'stop_location',
        'stop_order',
        'pickup_time',
        'drop_time',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'stop_order' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
} 