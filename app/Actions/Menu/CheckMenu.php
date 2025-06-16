<?php

namespace App\Actions\Menu;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class CheckMenu
{
    protected $activeRoles;

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(string $menuCode, array $activeRoles)
    {
        $this->activeRoles = $activeRoles;

        $menu = Menu::query()
            ->whereHas("permission", function ($query) {
                return $query->whereHas("roles", function ($query) {
                    return $query->whereIn("id", $this->activeRoles);
                });
            })
            ->where("code", $menuCode)
            ->orderBy('order')
            ->first();

        return $menu ? true : false;
    }
}
