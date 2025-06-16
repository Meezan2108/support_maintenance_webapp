<?php

namespace App\Http\Middleware;

use App\Actions\Menu\CheckMenu;
use App\Models\Menu;
use App\Models\MenuAction;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MenuAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $menuCode = $request->segment(1);

        $user = Auth::user();
        $roles = [];
        if ($user) {
            $roles = $user->roles->pluck('id')->toArray();
        }

        $isAllowed = (new CheckMenu())->execute($menuCode, $roles);
        if (!$isAllowed) {
            abort(403);
        }

        return $next($request);
    }
}
