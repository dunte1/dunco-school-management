<?php

namespace Modules\Transport\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_number',
        'vehicle_type',
        'brand',
        'model',
        'year',
        'capacity',
        'driver_id',
        'status',
        'registration_number',
        'insurance_expiry',
        'fitness_expiry',
        'permit_expiry',
        'fuel_type',
        'mileage',
        'description',
        'school_id'
    ];

    protected $casts = [
        'insurance_expiry' => 'date',
        'fitness_expiry' => 'date',
        'permit_expiry' => 'date',
        'year' => 'integer',
        'capacity' => 'integer',
        'mileage' => 'integer',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'vehicle_routes');
    }

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class);
    }
} 