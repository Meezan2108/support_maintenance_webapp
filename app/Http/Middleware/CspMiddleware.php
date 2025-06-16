<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CspMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $scriptSources = [
            "'self'", // Allow scripts from your own domain
            "'unsafe-eval'",
            "https://www.google.com",
            "https://www.gstatic.com"
        ];

        $imgSources = [
            "'self'",
            "data:",
            "blob:"
        ];

        $styleSources = [
            "'self'",
            "'unsafe-inline'",
            "https://fonts.googleapis.com",
            "https://cdn.datatables.net"
        ];

        $cspPolicy = "script-src " . implode(" ", $scriptSources) . "; "
            . "img-src " . implode(" ", $imgSources) . "; "
            . "style-src " . implode(" ", $styleSources) . ";";

        $response->header('Content-Security-Policy', $cspPolicy);

        return $response;
    }
}
