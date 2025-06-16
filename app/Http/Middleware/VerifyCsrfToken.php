<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function tokensMatch($request)
    {
        $token = $this->getTokenFromCookies($request);

        return is_string($request->session()->token()) &&
            is_string($token) &&
            hash_equals($request->session()->token(), $token);
    }

    protected function getTokenFromCookies(Request $request)
    {
        return $request->cookie("XSRF-TOKEN", "");
    }

    /**
     * Add the CSRF token to the response cookies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return \Illuminate\Http\Response
     */
    protected function addCookieToResponse($request, $response)
    {
        $response->headers->setCookie(
            new Cookie(
                'XSRF-TOKEN',
                $request->session()->token(),
                time() + 60 * 120,
                '/',
                null,
                config('session.secure'),
                config('session.http_only_token')
            )
        );

        return $response;
    }
}
