<?php

namespace Modules\Transport\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'license_number',
        'license_expiry',
        'phone',
        'email',
        'address',
        'date_of_birth',
        'joining_date',
        'salary',
        'status',
        'emergency_contact',
        'blood_group',
        'experience_years',
        'school_id'
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'salary' => 'decimal:2',
        'experience_years' => 'integer',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class);
    }
} 