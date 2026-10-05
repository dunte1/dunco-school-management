<?php

namespace Modules\ChatBot\Models;

use Illuminate\Database\Eloquent\Model;

class Index extends Model
{
    protected $table = 'chatbot_indexes';
    protected $fillable = ['tenant_id','document_id','chunk_index','chunk','embedding'];
    protected $casts = [
        'tenant_id' => 'integer',
        'document_id' => 'integer',
        'chunk_index' => 'integer',
        'embedding' => 'array',
    ];
}


