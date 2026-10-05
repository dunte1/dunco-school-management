<?php

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
        'frequency',
        'start_date',
        'end_date',
        'time',
        'days_of_week',
        'is_active',
        'template_id',
        'recipients',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'time' => 'datetime',
        'days_of_week' => 'array',
        'recipients' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByFrequency($query, $frequency)
    {
        return $query->where('frequency', $frequency);
    }

    public function isActive()
    {
        return $this->is_active && 
               $this->start_date <= now() && 
               (!$this->end_date || $this->end_date >= now());
    }

    public function getNextRunDate()
    {
        if (!$this->isActive()) {
            return null;
        }

        $now = now();
        $nextRun = $this->time;

        // If the scheduled time has passed today, move to next occurrence
        if ($nextRun->format('H:i') <= $now->format('H:i')) {
            switch ($this->frequency) {
                case 'daily':
                    $nextRun = $nextRun->addDay();
                    break;
                case 'weekly':
                    $nextRun = $nextRun->addWeek();
                    break;
                case 'monthly':
                    $nextRun = $nextRun->addMonth();
                    break;
                case 'yearly':
                    $nextRun = $nextRun->addYear();
                    break;
            }
        }

        return $nextRun;
    }
}
