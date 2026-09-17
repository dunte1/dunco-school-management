<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Basic, safe defaults. Tune CSP as needed for your frontend assets.
        // Allow inline styles (Tailwind) but block inline scripts
        $contentSecurityPolicy = "default-src 'self'; img-src 'self' data: https:; font-src 'self' data:; style-src 'self' 'unsafe-inline'; script-src 'self'; connect-src 'self';";

        $response->headers->set('Content-Security-Policy', $contentSecurityPolicy, false);
        $response->headers->set('X-Frame-Options', 'DENY', false);
        $response->headers->set('X-Content-Type-Options', 'nosniff', false);
        $response->headers->set('Referrer-Policy', 'no-referrer', false);
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()', false);

        // Add HSTS only when the request is over HTTPS to avoid local dev issues
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload', false);
        }

        return $response;
    }
}


