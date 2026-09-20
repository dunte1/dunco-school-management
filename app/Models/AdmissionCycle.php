<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionCycle extends Model
{
    protected $fillable = ['name', 'academic_year', 'opening_date', 'closing_date', 'description', 'status', 'is_active'];

    protected $casts = ['opening_date' => 'date', 'closing_date' => 'date', 'is_active' => 'boolean'];

    public function applications() { return $this->hasMany(AdmissionApplication::class); }
    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopeOpen($q) { return $q->where('status', 'open')->where('is_active', true); }

    public function isOpen(): bool
    {
        $now = now();
        return $this->status === 'open' && $this->is_active && $now->gte($this->opening_date) && $now->lte($this->closing_date);
    }
}
