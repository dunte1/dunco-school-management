<?php

namespace Modules\Examination\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_online',
        'is_active'
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnline($query)
    {
        return $query->where('is_online', true);
    }
} 