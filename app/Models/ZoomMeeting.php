<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'zoom_meeting_id',
        'topic',
        'start_time',
        'duration',
        'join_url',
        'password',
        'host_id',
        'host_name',
        'description',
        'status',
        'school_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'duration' => 'integer',
    ];

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function school()
    {
        return $this->belongsTo(\Modules\Core\Entities\School::class);
    }
}
