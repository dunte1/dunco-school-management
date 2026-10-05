<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'helpdesk_tickets';

    protected $fillable = [
        'school_id','subject','description','status','priority','assigned_to','created_by','closed_at'
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }
}
