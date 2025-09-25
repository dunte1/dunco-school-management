<?php

namespace Modules\Hostel\Models;

use Illuminate\Database\Eloquent\Model;

class HostelFee extends Model
{
    protected $fillable = [
        'hostel_id',
        'room_id',
        'bed_id',
        'student_id',
        'amount',
        'status', // paid, unpaid, overdue
        'invoice_id',
        'due_date',
        'paid_at',
        'fine',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fine' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function hostel() { return $this->belongsTo(Hostel::class); }
    public function room() { return $this->belongsTo(Room::class); }
    public function bed() { return $this->belongsTo(Bed::class); }
    public function student() { return $this->belongsTo(\App\Models\User::class, 'student_id'); }
} 