<?php

namespace App\Services\Auth;

use App\Services\Auth\Contracts\AuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MockAuthService implements AuthService
{
    /**
     * @var array
     */
    private $sessions = [
        1 => [
            "user" => [
                "name" => "Admin",
                "code" => "admin",
                "ref_division_id" => null,
                "ref_position_id" => null,
                "tel_no" => "6012345023",
                "fax_no" => "60123456",
                "email" => "admin@email.com",
                "roles" => [
                    "Super Admin"
                ]
            ]
        ],
        2 => [
            "user" => [
                "name" => "LKM Director",
                "code" => "lkm-director",
                "ref_division_id" => null,
                "ref_position_id" => null,
                "tel_no" => "6012345023",
                "fax_no" => "60123456",
                "email" => "director@email.com",
                "roles" => [
                    "LKM Director"
                ]
            ]
        ],
        3 => [
            "user" => [
                "name" => "R&D Coordinator",
                "code" => "rnd-coordinator",
                "ref_division_id" => null,
                "ref_position_id" => null,
                "tel_no" => "6012345023",
                "fax_no" => "60123456",
                "email" => "rnd@email.com",
                "roles" => [
                    "R&D Coordinator"
                ]
            ]
        ],
        4 => [
            "user" => [
                "name" => "Division Director",
                "code" => "division-director",
                "ref_division_id" => null,
                "ref_position_id" => null,
                "tel_no" => "6012345023",
                "fax_no" => "60123456",
                "email" => "division@email.com",
                "roles" => [
                    "Division Director"
                ]
            ]
        ],
        5 => [
            "user" => [
                "name" => "Public 1",
                "code" => "public-1",
                "ref_division_id" => null,
                "ref_position_id" => null,
                "tel_no" => "6012345023",
                "fax_no" => "60123456",
                "email" => "public1@email.com",
                "roles" => [
                    "Researcher"
                ]
            ]
        ],
    ];

    /**
     * Authenticate the user
     *
     * @param  Request  $request
     * @return array
     * @throws Exception
     */
    public function auth(string $sso_cookie, int $app_id = 0): array
    {
        return $this->sessions[$sso_cookie] ?? false;
    }

    public function getLoginUrl(string $path, $idUser = 1): string
    {
        $param = [
            'redirect_url' => $path,
        ];

        $loginUrl = config('app.url') . "/auth_callback?";
        return  $loginUrl . Arr::query($param);
    }
}
