<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        // Trust proxies may be needed depending on deployment; keep logic simple here.
        if (config('app.env') === 'production' && !$request->isSecure()) {
            $httpsUrl = 'https://'.$request->getHttpHost().$request->getRequestUri();
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}


