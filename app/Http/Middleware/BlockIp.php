<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class BlockIp
{
    public function handle(Request $request, Closure $next)
    {
        $list = '';
        try {
            $list = (string) (Setting::where('key', 'blocked_ips')->value('value') ?? '');
        } catch (\Throwable $e) {
            // DB not available (e.g., during install/cli). Proceed without blocking.
            $list = '';
        }
        if ($list !== '') {
            $ips = array_filter(array_map('trim', explode(',', $list)));
            if (in_array($request->ip(), $ips, true)) {
                return response('Your IP has been blocked.', 403);
            }
        }
        return $next($request);
    }
}


