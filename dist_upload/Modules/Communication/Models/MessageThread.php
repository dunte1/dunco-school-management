<?php

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
    ];

    public function participants()
    {
        return $this->hasMany(MessageParticipant::class, 'thread_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'thread_id');
    }
}
