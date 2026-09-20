<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionSection extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];

    public function fields() { return $this->hasMany(AdmissionField::class)->orderBy('sort_order'); }
    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopeOrdered($q) { return $q->orderBy('sort_order'); }
}
