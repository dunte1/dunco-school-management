<?php

namespace Modules\Portal\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'portal_messages';

    protected $fillable = ['sender_id', 'receiver_id', 'message', 'is_read'];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function sender()
    {
        return $this->belongsTo(\App\Models\User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(\App\Models\User::class, 'receiver_id');
    }
}
