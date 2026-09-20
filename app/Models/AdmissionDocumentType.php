<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionDocumentType extends Model
{
    protected $table = 'admission_documents';
    protected $fillable = ['name', 'is_required', 'allowed_types', 'max_size_kb', 'is_active', 'sort_order'];
    protected $casts = ['is_required' => 'boolean', 'is_active' => 'boolean', 'max_size_kb' => 'integer', 'sort_order' => 'integer'];

    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopeOrdered($q) { return $q->orderBy('sort_order'); }
}
