<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadActivity extends Model
{
    protected $fillable = ['lead_id', 'user_id', 'activity_type', 'description'];

    public function lead() { return $this->belongsTo(Lead::class); }
    public function user() { return $this->belongsTo(\App\Models\User::class); }
}
