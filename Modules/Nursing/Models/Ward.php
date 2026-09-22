<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ward extends Model
{
    protected $table = 'nursing_wards';

    protected $fillable = [
        'facility_id',
        'department_id',
        'name',
        'code',
        'floor',
        'capacity',
        'description',
        'is_active',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
    ];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(FacilityDepartment::class, 'department_id');
    }

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class);
    }
}
