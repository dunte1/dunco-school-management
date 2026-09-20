<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemoRequest extends Model
{
    protected $fillable = ['reference', 'name', 'institution', 'email', 'phone', 'number_of_students', 'current_system', 'modules', 'preferred_date', 'preferred_time', 'message', 'status', 'notes', 'assigned_to'];

    protected $casts = ['modules' => 'array', 'preferred_date' => 'date'];

    public function scopeNew($query) { return $query->where('status', 'new'); }
    public function scopeByStatus($query, $s) { return $query->where('status', $s); }
}
