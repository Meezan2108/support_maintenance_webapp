<?php

namespace App\Actions\Menu;

use App\Models\Menu;
use Illuminate\Support\Facades\Cache;

class GetMenusWithPermission
{
    protected $activeRoles;

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute($forceNoCache = true)
    {
        $cacheKey = "menu_permission";
        if (Cache::has($cacheKey) && !$forceNoCache) {
            $menus = Cache::get($cacheKey);
            if ($menus) return $menus;
        }

        $menus = Menu::query()
            ->with(["permission", "children.permission", "children.children.permission"])
            ->doesntHave('parent')
            ->orderBy('order')
            ->get();

        Cache::put($cacheKey, $menus, 3 * 60 * 60);
        return $menus;
    }
}
