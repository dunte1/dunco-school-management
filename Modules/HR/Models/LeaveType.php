<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $table = 'leave_types';
    protected $fillable = ['name', 'description'];
} 