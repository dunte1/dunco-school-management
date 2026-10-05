<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class FailedLoginAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'attempted_password_hash',
        'failure_type',
        'attempts_count',
        'first_attempt_at',
        'last_attempt_at',
        'blocked_until',
        'is_blocked',
        'country',
        'city',
        'is_suspicious_pattern',
        'attack_patterns',
    ];

    protected $casts = [
        'first_attempt_at' => 'datetime',
        'last_attempt_at' => 'datetime',
        'blocked_until' => 'datetime',
        'is_blocked' => 'boolean',
        'is_suspicious_pattern' => 'boolean',
        'attack_patterns' => 'array',
    ];

    protected $dates = [
        'first_attempt_at',
        'last_attempt_at',
        'blocked_until',
    ];

    /**
     * Scope for blocked attempts
     */
    public function scopeBlocked($query)
    {
        return $query->where('is_blocked', true);
    }

    /**
     * Scope for currently blocked attempts
     */
    public function scopeCurrentlyBlocked($query)
    {
        return $query->where('is_blocked', true)
                    ->where('blocked_until', '>', now());
    }

    /**
     * Scope for suspicious patterns
     */
    public function scopeSuspicious($query)
    {
        return $query->where('is_suspicious_pattern', true);
    }

    /**
     * Scope for attempts by IP
     */
    public function scopeByIp($query, $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }

    /**
     * Scope for attempts by email
     */
    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Scope for attempts within time range
     */
    public function scopeWithinTimeRange($query, $minutes = 15)
    {
        return $query->where('last_attempt_at', '>=', now()->subMinutes($minutes));
    }

    /**
     * Check if IP is currently blocked
     */
    public static function isIpBlocked($ipAddress)
    {
        return static::where('ip_address', $ipAddress)
                    ->currentlyBlocked()
                    ->exists();
    }

    /**
     * Check if email is currently blocked
     */
    public static function isEmailBlocked($email)
    {
        return static::where('email', $email)
                    ->currentlyBlocked()
                    ->exists();
    }

    /**
     * Record a failed login attempt
     */
    public static function recordAttempt($email, $ipAddress, $userAgent = null, $failureType = 'invalid_password')
    {
        $existing = static::where('email', $email)
                         ->where('ip_address', $ipAddress)
                         ->where('last_attempt_at', '>=', now()->subHours(1))
                         ->first();

        if ($existing) {
            $existing->increment('attempts_count');
            $existing->last_attempt_at = now();
            $existing->failure_type = $failureType;

            // Block if too many attempts
            if ($existing->attempts_count >= config('security.max_failed_attempts', 5)) {
                $existing->is_blocked = true;
                $existing->blocked_until = now()->addMinutes(config('security.lockout_duration', 15));
            }

            // Check for suspicious patterns
            if ($existing->attempts_count >= 3) {
                $existing->is_suspicious_pattern = true;
                $existing->attack_patterns = static::detectAttackPatterns($existing);
            }

            $existing->save();
            return $existing;
        }

        return static::create([
            'email' => $email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'failure_type' => $failureType,
            'attempts_count' => 1,
            'first_attempt_at' => now(),
            'last_attempt_at' => now(),
            'country' => static::getCountryFromIp($ipAddress),
            'city' => static::getCityFromIp($ipAddress),
        ]);
    }

    /**
     * Clear expired blocks
     */
    public static function clearExpiredBlocks()
    {
        static::where('is_blocked', true)
              ->where('blocked_until', '<=', now())
              ->update([
                  'is_blocked' => false,
                  'blocked_until' => null,
              ]);
    }

    /**
     * Get failed attempts count for IP in time window
     */
    public static function getAttemptsCountForIp($ipAddress, $minutes = 15)
    {
        return static::where('ip_address', $ipAddress)
                    ->withinTimeRange($minutes)
                    ->sum('attempts_count');
    }

    /**
     * Get failed attempts count for email in time window
     */
    public static function getAttemptsCountForEmail($email, $minutes = 15)
    {
        return static::where('email', $email)
                    ->withinTimeRange($minutes)
                    ->sum('attempts_count');
    }

    /**
     * Get cached statistics
     */
    public static function getCachedStats($hours = 24)
    {
        return Cache::remember("failed_login_stats_{$hours}", 300, function () use ($hours) {
            $since = now()->subHours($hours);

            return [
                'total_attempts' => static::where('created_at', '>=', $since)->sum('attempts_count'),
                'unique_ips' => static::where('created_at', '>=', $since)->distinct('ip_address')->count(),
                'unique_emails' => static::where('created_at', '>=', $since)->distinct('email')->count(),
                'currently_blocked' => static::currentlyBlocked()->count(),
                'suspicious_patterns' => static::suspicious()->where('created_at', '>=', $since)->count(),
                'top_failure_types' => static::where('created_at', '>=', $since)
                                            ->select('failure_type')
                                            ->selectRaw('COUNT(*) as count')
                                            ->groupBy('failure_type')
                                            ->orderByDesc('count')
                                            ->limit(5)
                                            ->get()
                                            ->pluck('count', 'failure_type'),
            ];
        });
    }

    /**
     * Detect attack patterns based on attempts
     */
    private static function detectAttackPatterns($attempt)
    {
        $patterns = [];

        // Check for rapid succession attempts
        if ($attempt->attempts_count > 10) {
            $patterns[] = 'brute_force';
        }

        // Check for common password patterns
        if ($attempt->failure_type === 'invalid_password' && $attempt->attempts_count > 5) {
            $patterns[] = 'password_spray';
        }

        // Check for multiple emails from same IP
        $emailsFromIp = static::where('ip_address', $attempt->ip_address)
                             ->distinct('email')
                             ->count();
        if ($emailsFromIp > 5) {
            $patterns[] = 'credential_stuffing';
        }

        return $patterns;
    }

    /**
     * Get country from IP address (placeholder)
     */
    private static function getCountryFromIp($ipAddress)
    {
        // Placeholder - integrate with GeoIP service
        if ($ipAddress === '127.0.0.1' || $ipAddress === '::1') {
            return 'localhost';
        }
        return null;
    }

    /**
     * Get city from IP address (placeholder)
     */
    private static function getCityFromIp($ipAddress)
    {
        // Placeholder - integrate with GeoIP service
        if ($ipAddress === '127.0.0.1' || $ipAddress === '::1') {
            return 'localhost';
        }
        return null;
    }

    /**
     * Clean up old failed attempts
     */
    public static function cleanup($days = 30)
    {
        static::where('created_at', '<', now()->subDays($days))->delete();
    }

    /**
     * Get location display
     */
    public function getLocationDisplayAttribute()
    {
        $parts = array_filter([$this->city, $this->country]);
        return implode(', ', $parts) ?: 'Unknown Location';
    }

    /**
     * Check if this attempt is part of an ongoing attack
     */
    public function isPartOfAttack()
    {
        return $this->is_suspicious_pattern || $this->attempts_count >= 5;
    }

    /**
     * Get time until unblocked
     */
    public function getTimeUntilUnblockedAttribute()
    {
        if (!$this->is_blocked || !$this->blocked_until) {
            return null;
        }

        if ($this->blocked_until <= now()) {
            return 'Expired';
        }

        return $this->blocked_until->diffForHumans();
    }
}
