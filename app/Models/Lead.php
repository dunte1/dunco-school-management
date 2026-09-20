<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['source', 'name', 'email', 'phone', 'organization', 'status', 'notes', 'converted_at'];
    protected $casts = ['converted_at' => 'datetime'];

    public function activities() { return $this->hasMany(LeadActivity::class)->orderBy('created_at', 'desc'); }
    public function scopeByStatus($query, $s) { return $query->where('status', $s); }

    public function logActivity(string $type, string $desc, $userId = null): LeadActivity
    {
        return $this->activities()->create(['activity_type' => $type, 'description' => $desc, 'user_id' => $userId]);
    }
}
