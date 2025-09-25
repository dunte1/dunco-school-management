<?php

namespace Modules\Timetable\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $table = 'timetables'; // Adjust if your table name is different
    protected $fillable = [
        'name',
        // add other fillable fields as needed
    ];
    // Add fillable, relationships, etc. as needed
} 