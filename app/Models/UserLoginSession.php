<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class UserLoginSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_id',
        'session_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'country',
        'city',
        'latitude',
        'longitude',
        'login_method',
        'is_successful',
        'failure_reason',
        'login_at',
        'logout_at',
        'duration_seconds',
        'is_suspicious',
        'extra_data',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
        'is_successful' => 'boolean',
        'is_suspicious' => 'boolean',
        'extra_data' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected $dates = [
        'login_at',
        'logout_at',
    ];

    /**
     * Get the user that owns the login session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the school that the login session belongs to.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Scope for successful logins only
     */
    public function scopeSuccessful($query)
    {
        return $query->where('is_successful', true);
    }

    /**
     * Scope for failed logins only
     */
    public function scopeFailed($query)
    {
        return $query->where('is_successful', false);
    }

    /**
     * Scope for suspicious logins
     */
    public function scopeSuspicious($query)
    {
        return $query->where('is_suspicious', true);
    }

    /**
     * Scope for logins by school
     */
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Scope for logins by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for logins within date range
     */
    public function scopeWithinDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('login_at', [$startDate, $endDate]);
    }

    /**
     * Scope for active sessions (not logged out)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('logout_at');
    }

    /**
     * Get duration in a human-readable format
     */
    public function getHumanDurationAttribute()
    {
        if (!$this->duration_seconds) {
            return 'Unknown';
        }

        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m";
    }

    /**
     * Get location display string
     */
    public function getLocationDisplayAttribute()
    {
        $parts = array_filter([$this->city, $this->country]);
        return implode(', ', $parts) ?: 'Unknown Location';
    }

    /**
     * Get device display string
     */
    public function getDeviceDisplayAttribute()
    {
        if ($this->device_type && $this->browser) {
            return ucfirst($this->device_type) . ' - ' . $this->browser;
        }

        if ($this->device_type) {
            return ucfirst($this->device_type);
        }

        if ($this->browser) {
            return $this->browser;
        }

        return 'Unknown Device';
    }

    /**
     * Mark session as logged out
     */
    public function markAsLoggedOut()
    {
        $this->logout_at = now();
        if ($this->login_at) {
            $this->duration_seconds = $this->login_at->diffInSeconds(now());
        }
        $this->save();
    }

    /**
     * Mark session as suspicious
     */
    public function markAsSuspicious($reason = null)
    {
        $this->is_suspicious = true;
        if ($reason && $this->extra_data) {
            $extraData = $this->extra_data;
            $extraData['suspicious_reason'] = $reason;
            $this->extra_data = $extraData;
        } elseif ($reason) {
            $this->extra_data = ['suspicious_reason' => $reason];
        }
        $this->save();
    }

    /**
     * Get cached login statistics for a user
     */
    public static function getCachedStatsForUser($userId, $days = 30)
    {
        return Cache::remember("login_stats_{$userId}_{$days}", 300, function () use ($userId, $days) {
            $startDate = now()->subDays($days);

            return [
                'total_logins' => static::where('user_id', $userId)
                    ->where('login_at', '>=', $startDate)
                    ->count(),
                'successful_logins' => static::where('user_id', $userId)
                    ->where('login_at', '>=', $startDate)
                    ->successful()
                    ->count(),
                'failed_logins' => static::where('user_id', $userId)
                    ->where('login_at', '>=', $startDate)
                    ->failed()
                    ->count(),
                'unique_devices' => static::where('user_id', $userId)
                    ->where('login_at', '>=', $startDate)
                    ->distinct('device_type')
                    ->count('device_type'),
                'suspicious_logins' => static::where('user_id', $userId)
                    ->where('login_at', '>=', $startDate)
                    ->suspicious()
                    ->count(),
            ];
        });
    }

    /**
     * Get cached login statistics for a school
     */
    public static function getCachedStatsForSchool($schoolId, $days = 30)
    {
        return Cache::remember("school_login_stats_{$schoolId}_{$days}", 300, function () use ($schoolId, $days) {
            $startDate = now()->subDays($days);

            return [
                'total_logins' => static::where('school_id', $schoolId)
                    ->where('login_at', '>=', $startDate)
                    ->count(),
                'unique_users' => static::where('school_id', $schoolId)
                    ->where('login_at', '>=', $startDate)
                    ->distinct('user_id')
                    ->count('user_id'),
                'suspicious_logins' => static::where('school_id', $schoolId)
                    ->where('login_at', '>=', $startDate)
                    ->suspicious()
                    ->count(),
                'active_sessions' => static::where('school_id', $schoolId)
                    ->active()
                    ->count(),
            ];
        });
    }

    /**
     * Create a login session record
     */
    public static function createLoginSession($user, $request, $isSuccessful = true, $failureReason = null)
    {
        return static::create([
            'user_id' => $user ? $user->id : null,
            'school_id' => $user && $user->school_id ? $user->school_id : null,
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_type' => static::detectDeviceType($request->userAgent()),
            'browser' => static::detectBrowser($request->userAgent()),
            'platform' => static::detectPlatform($request->userAgent()),
            'login_method' => 'password', // default, can be overridden
            'is_successful' => $isSuccessful,
            'failure_reason' => $failureReason,
            'login_at' => now(),
        ]);
    }

    /**
     * Detect device type from user agent
     */
    private static function detectDeviceType($userAgent)
    {
        if (!$userAgent) return 'unknown';

        if (preg_match('/mobile|android|iphone|ipad/i', $userAgent)) {
            if (preg_match('/ipad/i', $userAgent)) return 'tablet';
            return 'mobile';
        }

        return 'desktop';
    }

    /**
     * Detect browser from user agent
     */
    private static function detectBrowser($userAgent)
    {
        if (!$userAgent) return 'unknown';

        if (preg_match('/chrome/i', $userAgent)) return 'Chrome';
        if (preg_match('/firefox/i', $userAgent)) return 'Firefox';
        if (preg_match('/safari/i', $userAgent) && !preg_match('/chrome/i', $userAgent)) return 'Safari';
        if (preg_match('/edge/i', $userAgent)) return 'Edge';
        if (preg_match('/opera/i', $userAgent)) return 'Opera';

        return 'unknown';
    }

    /**
     * Detect platform from user agent
     */
    private static function detectPlatform($userAgent)
    {
        if (!$userAgent) return 'unknown';

        if (preg_match('/windows/i', $userAgent)) return 'Windows';
        if (preg_match('/macintosh|mac os x/i', $userAgent)) return 'macOS';
        if (preg_match('/linux/i', $userAgent)) return 'Linux';
        if (preg_match('/android/i', $userAgent)) return 'Android';
        if (preg_match('/iphone|ipad|ipod/i', $userAgent)) return 'iOS';

        return 'unknown';
    }
}
