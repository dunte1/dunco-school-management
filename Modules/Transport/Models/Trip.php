<?php

namespace Modules\Transport\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'route_id',
        'trip_date',
        'start_time',
        'end_time',
        'status',
        'passenger_count',
        'fuel_consumed',
        'distance_covered',
        'notes',
        'school_id'
    ];

    protected $casts = [
        'trip_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'passenger_count' => 'integer',
        'fuel_consumed' => 'decimal:2',
        'distance_covered' => 'decimal:2',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function passengers()
    {
        return $this->belongsToMany(\Modules\Academic\app\Models\Student::class, 'trip_passengers');
    }

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class);
    }
} 