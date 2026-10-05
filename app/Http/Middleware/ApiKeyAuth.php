<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;

class ApiKeyAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Check for X-Api-Key header or api_key query parameter (standard)
        $key = $request->header('X-Api-Key') ?: $request->query('api_key');

        // Also check for Auth-Key header (used by Android app)
        if (!$key) {
            $key = $request->header('Auth-Key');
        }

        // Also check for Client-Service header (used by Android app)
        if (!$key) {
            $clientService = $request->header('Client-Service');
            if ($clientService === 'smartschool') {
                // Use a default API key for smartschool client
                $key = 'smartschool-mobile-default';
            }
        }

        if (!$key) {
            return response()->json(['error' => 'API key required'], 401);
        }

        $record = ApiKey::where('key', $key)->where('is_active', true)->first();
        if (!$record) {
            return response()->json(['error' => 'Invalid or unregistered app, please contact to smartschool admin'], 401);
        }

        $record->last_used_at = now();
        $record->save();

        if ($record->user_id) {
            auth()->loginUsingId($record->user_id, true);
        }

        return $next($request);
    }
}


