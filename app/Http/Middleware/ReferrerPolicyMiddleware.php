<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ReferrerPolicyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $response->header('Referrer-Policy', 'no-referrer'); // Set your default policy here
        return $response;
    }
}
