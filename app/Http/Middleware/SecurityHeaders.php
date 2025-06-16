<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->header('X-Frame-Options', 'SAMEORIGIN'); // Prevent framing
        $response->header('Referrer-Policy', 'same-origin'); // Set your default policy here
        $response->header('X-Content-Type-Options', 'nosniff'); // Prevent MIME type sniffing


        return $response;
    }
}
