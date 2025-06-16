<?php

namespace App\Http\Controllers;

use App\Actions\User\SyncUser;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Auth\Contracts\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends Controller
{
    /**
     * Logout the authenticated user
     *
     * @param  Request  $request
     * @return Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerate();

        return redirect()->route("login");
    }

    /**
     * Get the authenticated user
     *
     * @param  Request  $request
     * @return UserResource
     */
    public function getUser(Request $request): UserResource
    {
        return new UserResource($request->user()->load('roles'));
    }

    public function callback(Request $request, SyncUser $syncUser, AuthService $authService)
    {
        try {
            $cookiesKey = config('services.lkm.sso_key_name');
            $appId = config('services.lkm.sso_app_id');

            $data = $authService->auth($request->input($cookiesKey), $appId);

            $user = $syncUser->execute($data['user']);

            if ($this->login($request, $user)) {
                return redirect()->route('panel.dashboard');
            }
        } catch (Throwable $e) {
            report($e);
        }

        return abort(404);
    }


    /**
     * Log in the user
     *
     * @param  Request  $request
     * @param  User  $user
     * @return bool
     */
    private function login(Request $request, User $user): bool
    {
        $authLifetime = 60;

        try {
            if (Auth::id() != $user->id) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                Auth::login($user);
            }

            $lifetime = now()->addMinutes($authLifetime);
            $request->session()->put('auth_lifetime', $lifetime);
            $request->session()->regenerate();

            return true;
        } catch (Throwable $e) {
            report($e);
        }

        return false;
    }
}
