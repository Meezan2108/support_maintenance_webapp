<?php

namespace App\Http\Middleware;

use App\Actions\Menu\BuildMenu;
use App\Actions\MyTask\GetCountMyTask;
use App\Actions\Notification\GetCountUnreadNotification;
use App\Actions\Notification\GetNotificationNavbar;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $user = Auth::user();
        $roles = [];
        if ($user) {
            $roles = $user->roles->pluck('id')->toArray();
        }

        $menus = (new BuildMenu())->execute($roles);

        return array_merge(parent::share($request), [
            "menus" => $menus,
            "activeMenuCode" => $request->segment(1),
            "appBaseUrl" => url('/'),
            "authUser" => $user ? (new UserResource($user))->toArray($request) : null,
            "flash" => [
                "message" => fn () => $request->session()->get('message')
            ],
            'recaptchav2_sitekey' => config('recaptchav2.sitekey'),
        ]);
    }
}
