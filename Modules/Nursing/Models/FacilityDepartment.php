<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FacilityDepartment extends Model
{
    protected $table = 'nursing_facility_departments';

    protected $fillable = [
        'facility_id',
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function wards(): HasMany
    {
        return $this->hasMany(Ward::class, 'department_id');
    }

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class, 'department_id');
    }
}
