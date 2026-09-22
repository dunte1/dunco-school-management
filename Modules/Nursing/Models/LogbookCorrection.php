<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogbookCorrection extends Model
{
    protected $table = 'nursing_logbook_corrections';

    protected $fillable = [
        'logbook_id',
        'corrected_by',
        'previous_values',
        'new_values',
        'reason',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'previous_values' => 'array',
        'new_values' => 'array',
        'approved_at' => 'datetime',
    ];

    public function logbook(): BelongsTo
    {
        return $this->belongsTo(LogbookEntry::class, 'logbook_id');
    }

    public function correctedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'corrected_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }
}
