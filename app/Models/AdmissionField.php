<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionField extends Model
{
    protected $fillable = ['admission_section_id', 'name', 'slug', 'type', 'options', 'is_required', 'help_text', 'placeholder', 'validation_rules', 'sort_order', 'is_active'];
    protected $casts = ['options' => 'array', 'is_required' => 'boolean', 'is_active' => 'boolean', 'sort_order' => 'integer'];

    public function section() { return $this->belongsTo(AdmissionSection::class, 'admission_section_id'); }
    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopeOrdered($q) { return $q->orderBy('sort_order'); }
}
