<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StrictTransportSecurity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $maxAge = 31536000; // One year in seconds (adjust as needed)
        $preload = false; // Optional: Set to true to preload HSTS

        $hstsDirective = sprintf("max-age=%d; includeSubDomains; %s", $maxAge, $preload ? 'preload' : '');

        $response->header('Strict-Transport-Security', $hstsDirective);

        return $response;
    }
}
