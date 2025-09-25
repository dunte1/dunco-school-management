<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'name',
        'code',
        'logo',
        'theme',
        'motto',
        'domain',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the users for the school.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the settings for the school.
     */
    public function settings()
    {
        return $this->hasMany(SchoolSetting::class);
    }

    /**
     * Get the audit logs for the school.
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
