<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        $timeout = 0;
        try {
            $timeout = (int) (Setting::where('key', 'session_timeout_minutes')->value('value') ?? 0);
        } catch (\Throwable $e) {
            $timeout = 0;
        }
        if ($timeout > 0 && Auth::check()) {
            $key = 'last_activity_at';
            $last = session($key);
            $now = now()->timestamp;
            if ($last && ($now - (int)$last) > ($timeout * 60)) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();
                return redirect()->route('login')->withErrors(['session' => 'You were logged out due to inactivity.']);
            }
            session([$key => $now]);
        }
        return $next($request);
    }
}
