<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'capacity', 'location', 'description', 
        'is_active', 'equipment', 'features'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'equipment' => 'array',
        'features' => 'array',
    ];

    public function examSchedules()
    {
        return $this->hasMany(ExamSchedule::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query, $date, $time)
    {
        return $query->whereDoesntHave('examSchedules', function($q) use ($date, $time) {
            $q->where('scheduled_date', $date)
              ->where('scheduled_time', $time)
              ->whereIn('status', ['scheduled', 'active']);
        });
    }

    public function getOccupancyRateAttribute()
    {
        $totalCapacity = $this->capacity;
        if ($totalCapacity == 0) return 0;
        
        $currentBookings = $this->examSchedules()
            ->where('scheduled_date', now()->toDateString())
            ->whereIn('status', ['scheduled', 'active'])
            ->count();
            
        return round(($currentBookings / $totalCapacity) * 100, 2);
    }

    public function isAvailable($date, $time)
    {
        return $this->examSchedules()
            ->where('scheduled_date', $date)
            ->where('scheduled_time', $time)
            ->whereIn('status', ['scheduled', 'active'])
            ->count() == 0;
    }
}
