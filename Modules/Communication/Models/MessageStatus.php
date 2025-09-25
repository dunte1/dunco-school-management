<?php

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'user_id',
        'is_read',
        'is_deleted',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
