<?php

namespace App\Actions\Menu;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class BuildMenu
{
    protected $activeRoles;

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(array $activeRoles, $forceNoCache = false)
    {
        $cacheKey = "build_menu_" . implode("", $activeRoles);
        if (Cache::has($cacheKey) && !$forceNoCache) {
            $menus = Cache::get($cacheKey);
            if ($menus) return $menus;
        }
        $this->activeRoles = $activeRoles;

        $menus = Menu::query()
            ->with([
                "children" => function ($query) {
                    return $query->whereHas("permission", function ($query) {
                        return $query->whereHas("roles", function ($query) {
                            return $query->whereIn("id", $this->activeRoles);
                        });
                    });
                },
                "children.children" => function ($query) {
                    return $query->whereHas("permission", function ($query) {
                        return $query->whereHas("roles", function ($query) {
                            return $query->whereIn("id", $this->activeRoles);
                        });
                    });
                }
            ])
            ->doesntHave('parent')
            ->whereHas("permission", function ($query) {
                return $query->whereHas("roles", function ($query) {
                    return $query->whereIn("id", $this->activeRoles);
                });
            })
            ->orderBy('order')
            ->get();

        Cache::put($cacheKey, $menus, 3 * 60 * 60);
        return $menus;
    }
}
