<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Modify this CSP as needed!
            $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-eval' https://www.google.com https://www.gstatic.com 'unsafe-inline'; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
               "img-src 'self' data:; " .
               "font-src 'self' https://fonts.gstatic.com;";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
