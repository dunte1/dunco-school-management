<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     * Accepts IP addresses, CIDR notation, or '*'.
     */
    protected $proxies = null;

    /**
     * The headers to use to detect proxies.
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;

    public function __construct()
    {
        $env = env('TRUST_PROXIES');
        if ($env) {
            $this->proxies = $env === '*' ? '*' : array_map('trim', explode(',', $env));
        }
    }
}




























