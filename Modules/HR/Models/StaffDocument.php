<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class StaffDocument extends Model
{
    protected $table = 'staff_documents';
    protected $fillable = ['staff_id', 'type', 'file_path', 'description'];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
} 