<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Auth\Contracts\AuthService;

class CrimsAuthByIdService implements AuthService
{
    const AUTH_LIFETIME = 60;

    public function auth(string $employeeId, int $app_id): array
    {
        $user = User::with("roles")
            ->where("staf_id", $employeeId)
            ->first();

        $roles = $user->roles->pluck("id")->toArray();

        $user = $user->toArray();
        $user["roles"] = $roles;

        return ["user" => $user];
    }

    public function getLoginUrl($path = "/"): string
    {
        return '#';
    }
}
