<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $table = 'leave_types';
    
    protected $fillable = [
        'name', 
        'description', 
        'default_days', 
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'default_days' => 'integer',
    ];

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'type', 'name');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
} 