<?php

namespace Modules\Hostel\Models;

use Illuminate\Database\Eloquent\Model;

class HostelVisitor extends Model
{
    protected $fillable = [
        'hostel_id',
        'student_id',
        'visitor_name',
        'visitor_contact',
        'purpose',
        'time_in',
        'time_out',
        'pass_number',
        'notes',
    ];

    protected $casts = [
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];

    public function hostel() { return $this->belongsTo(Hostel::class); }
    public function student() { return $this->belongsTo(\App\Models\User::class, 'student_id'); }
} 