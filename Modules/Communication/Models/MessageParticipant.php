<?php

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'thread_id',
        'user_id',
    ];

    public function thread()
    {
        return $this->belongsTo(MessageThread::class, 'thread_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
