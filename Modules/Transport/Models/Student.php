<?php

namespace Modules\Transport\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $table = 'transport_students';
    
    protected $fillable = [
        'name',
        'student_id',
        'parent_name',
        'parent_phone',
        'parent_email',
        'pickup_location',
        'dropoff_location',
        'route_id',
        'vehicle_id',
        'pickup_time',
        'dropoff_time',
        'monthly_fee',
        'status',
        'emergency_contact',
        'emergency_phone',
        'created_by',
        'school_id'
    ];

    protected $casts = [
        'pickup_time' => 'datetime',
        'dropoff_time' => 'datetime',
        'monthly_fee' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the route that the student is assigned to.
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    /**
     * Get the vehicle that the student is assigned to.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    /**
     * Get the fees for this student.
     */
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'student_id');
    }

    /**
     * Get the payments for this student.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'student_id');
    }

    /**
     * Get the trips for this student.
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'student_id');
    }

    /**
     * Scope for active students.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive students.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope for suspended students.
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Scope for students by route.
     */
    public function scopeByRoute($query, $routeId)
    {
        return $query->where('route_id', $routeId);
    }

    /**
     * Scope for students by vehicle.
     */
    public function scopeByVehicle($query, $vehicleId)
    {
        return $query->where('vehicle_id', $vehicleId);
    }

    /**
     * Get the full name with student ID.
     */
    public function getFullNameWithIdAttribute()
    {
        return $this->name . ' (' . $this->student_id . ')';
    }

    /**
     * Get the pickup and dropoff locations.
     */
    public function getRouteLocationsAttribute()
    {
        return $this->pickup_location . ' → ' . $this->dropoff_location;
    }

    /**
     * Get the pickup and dropoff times.
     */
    public function getRouteTimesAttribute()
    {
        return $this->pickup_time?->format('H:i') . ' - ' . $this->dropoff_time?->format('H:i');
    }
}
