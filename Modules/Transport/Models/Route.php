<?php

namespace Modules\Transport\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'start_location',
        'end_location',
        'distance',
        'estimated_time',
        'fare',
        'description',
        'status',
        'school_id'
    ];

    protected $casts = [
        'distance' => 'decimal:2',
        'fare' => 'decimal:2',
        'estimated_time' => 'integer', // in minutes
    ];

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'vehicle_routes');
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function stops()
    {
        return $this->hasMany(RouteStop::class);
    }

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class);
    }
} 