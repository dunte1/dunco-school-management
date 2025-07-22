<?php

namespace Modules\Examination\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProctoringLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'exam_attempt_id',
        'event_type',
        'description',
        'event_data',
        'severity',
        'is_resolved',
        'resolution_notes',
        'resolved_by',
        'resolved_at'
    ];

    protected $casts = [
        'event_data' => 'array',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime'
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'resolved_by');
    }

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeByEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    public function scopeCritical($query)
    {
        return $query->whereIn('severity', ['high', 'critical']);
    }

    public function getEventTypeLabelAttribute()
    {
        return match($this->event_type) {
            'tab_switch' => 'Tab Switch',
            'window_focus' => 'Window Focus',
            'copy_paste' => 'Copy/Paste',
            'right_click' => 'Right Click',
            'keyboard_shortcut' => 'Keyboard Shortcut',
            'multiple_faces' => 'Multiple Faces',
            'no_face' => 'No Face Detected',
            'voice_detected' => 'Voice Detected',
            'screen_share' => 'Screen Share',
            'browser_dev_tools' => 'Browser Dev Tools',
            'suspicious_activity' => 'Suspicious Activity',
            default => ucfirst(str_replace('_', ' ', $this->event_type))
        };
    }

    public function getSeverityColorAttribute()
    {
        return match($this->severity) {
            'low' => 'blue',
            'medium' => 'yellow',
            'high' => 'orange',
            'critical' => 'red',
            default => 'gray'
        };
    }
} 