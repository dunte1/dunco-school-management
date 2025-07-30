<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasPermissions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'school_id',
        'primary_role_id',
        'phone',
        'address',
        'avatar',
        'is_active',
        'force_password_reset',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'settings' => 'array',
        ];
    }

    /**
     * Get the school that the user belongs to.
     */
    public function school()
    {
        return $this->belongsTo(\App\Models\School::class, 'school_id');
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(\App\Models\Role::class, 'role_user', 'user_id', 'role_id')
            ->withPivot('school_id');
    }

    public function rolesForSchool($schoolId = null)
    {
        return $this->roles()->wherePivot('school_id', $schoolId)->get();
    }

    /**
     * The permissions that belong to the user through roles.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id')
            ->withTimestamps();
    }

    /**
     * Send parent account notification with credentials.
     */
    public function sendParentAccountNotification($password)
    {
        $this->notify(new \App\Notifications\ParentAccountCreated($this, $password));
    }

    /**
     * Get the user's notification preferences as an array.
     */
    public function getNotificationPreferencesAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Set the user's notification preferences.
     */
    public function setNotificationPreferencesAttribute($value)
    {
        $this->attributes['notification_preferences'] = json_encode($value);
    }

    /**
     * Get a specific notification preference (default true if not set)
     */
    public function prefersNotification($type)
    {
        $prefs = $this->notification_preferences;
        return isset($prefs[$type]) ? (bool)$prefs[$type] : true;
    }

    public function getSetting($key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    public function setSetting($key, $value)
    {
        $settings = $this->settings ?? [];
        data_set($settings, $key, $value);
        $this->settings = $settings;
        $this->save();
    }

    public function primaryRole()
    {
        return $this->belongsTo(\App\Models\Role::class, 'primary_role_id');
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\EmailVerificationNotification());
    }

    /**
     * Activate the user account.
     */
    public function activate()
    {
        $this->update([
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
