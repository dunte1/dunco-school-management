<?php

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;

class MessageDeliveryLog extends Model
{
    protected $table = 'message_delivery_logs';
    protected $fillable = [
        'message_id',
        'recipient_id',
        'channel',
        'status',
        'response',
    ];
} 