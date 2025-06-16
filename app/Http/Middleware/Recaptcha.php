<?php

/**
 * @file app/Http/Middleware/Recaptcha.php
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;
use ReCaptcha\ReCaptcha as GoogleRecaptcha;

class Recaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config("app.env") == "production") {
            $response = (new GoogleRecaptcha(config('recaptchav2.secret')))
                ->verify($request->input('recaptcha'), $request->ip());

            if (!$response->isSuccess()) {
                throw ValidationException::withMessages([
                    "recaptcha" => 'Recaptcha failed. Please try again.'
                ]);
            }
        }

        return $next($request);
    }
}
