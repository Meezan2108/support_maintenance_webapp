<?php

namespace App\Services\Auth\Contracts;

use Exception;

interface AuthService
{
    /**
     * Authenticate the user
     *
     * @param string  $sso_cookie
     * @param int $app_id
     *
     * @return array
     * @throws Exception
     */
    public function auth(string $sso_cookie, int $app_id): array;

    public function getLoginUrl(string $path): String;
}
