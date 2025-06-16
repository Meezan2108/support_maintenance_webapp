<?php

namespace App\Services\Auth;

use App\Services\Auth\Contracts\AuthService;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Arr;

class CrimsAuthService implements AuthService
{
    const AUTH_LIFETIME = 60;

    public function auth(string $sso_cookie, int $app_id): array
    {
        $payload = [
            config('services.lkm.gate_sso_cookie') => $sso_cookie,
            'app_id' => $app_id
        ];
        //\Log::debug(config('url.service.auth').'/api/auth');
        $response = Http::withoutVerifying()
            ->retry(3, 100)
            ->post(config('services.lkm.auth_url') . '/api/auth', $payload)
            ->throw();

        $body = $response->json();

        if ($body['status'] == 0) {
            throw new HttpClientException($response['msg']);
        }

        return $body['data'];
    }

    public function getLoginUrl($path = "/"): string
    {
        $param = [
            'app' => config('services.lkm.app_kode'),
            'redirect_url' => $path,
            'auth_callback_url' => '/auth_callback'
        ];

        if (config('app.env') == 'local') {
            $param['app_url'] = config('app.url');
        }

        $loginUrl = rtrim(config('services.lkm.auth_url'), '/') . "/login?";
        return  $loginUrl . Arr::query($param);
    }
}
