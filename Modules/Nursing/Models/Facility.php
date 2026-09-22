<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facility extends Model
{
    use SoftDeletes;

    protected $table = 'nursing_facilities';

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'type',
        'address',
        'city',
        'state',
        'country',
        'phone',
        'email',
        'contact_person',
        'contact_person_phone',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(FacilityDepartment::class);
    }

    public function wards(): HasMany
    {
        return $this->hasMany(Ward::class);
    }

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class);
    }
}
