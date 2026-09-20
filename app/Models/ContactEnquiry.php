<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactEnquiry extends Model
{
    protected $fillable = ['reference', 'name', 'email', 'phone', 'organization', 'subject', 'message', 'status', 'notes', 'assigned_to'];

    public function scopeNew($query) { return $query->where('status', 'new'); }
    public function scopeByStatus($query, $s) { return $query->where('status', $s); }
}
