<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectAuditLog extends Model
{
    protected $table = 'subject_audit_logs';
    protected $fillable = ['subject_id', 'user_id', 'action', 'changes'];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
} 