<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Failed as AuthFailed;
use Illuminate\Auth\Events\Lockout as AuthLockout;
use Illuminate\Auth\Events\Login as AuthLogin;
use Illuminate\Auth\Events\Logout as AuthLogout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SecurityAlertMail;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // Map events to listeners here if needed in the future
    ];

    public function boot(): void
    {
        // Inline listeners for suspicious activity and login events
        $alertRecipients = [
            'info@duncowebsolutions.co.ke',
            'dunthecan02@gmail.com',
        ];

        $sendAlert = function (string $subject, array $payload = []) use ($alertRecipients) {
            try {
                foreach ($alertRecipients as $recipient) {
                    Mail::to($recipient)->send(new SecurityAlertMail($subject, $payload));
                }
            } catch (\Throwable $e) {
                Log::error('Failed to send security alert email', [
                    'error' => $e->getMessage(),
                    'subject' => $subject,
                ]);
            }
        };

        $this->app['events']->listen(AuthFailed::class, function (AuthFailed $event) use ($sendAlert) {
            $payload = [
                'email' => is_array($event->credentials) ? ($event->credentials['email'] ?? null) : null,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toDateTimeString(),
            ];
            Log::warning('Authentication failed', $payload);
            $sendAlert('Suspicious activity: failed login attempt', $payload);
        });

        $this->app['events']->listen(AuthLockout::class, function (AuthLockout $event) use ($sendAlert) {
            $payload = [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toDateTimeString(),
            ];
            Log::warning('User temporarily locked out due to too many attempts', $payload);
            $sendAlert('Suspicious activity: user locked out', $payload);
        });

        $this->app['events']->listen(AuthLogin::class, function (AuthLogin $event) {
            Log::info('User login', [
                'user_id' => $event->user?->id,
                'ip' => request()->ip(),
            ]);
        });

        $this->app['events']->listen(AuthLogout::class, function (AuthLogout $event) {
            Log::info('User logout', [
                'user_id' => $event->user?->id,
                'ip' => request()->ip(),
            ]);
        });
    }
}
