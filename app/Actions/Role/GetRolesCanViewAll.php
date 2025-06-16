<?php

namespace App\Actions\Role;

use App\Models\User;
use Spatie\Permission\Models\Role;

class GetRolesCanViewAll
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute()
    {
        $roles = Role::whereIn('id', [
            User::ROLE_LKM_DIRECTOR,
            User::ROLE_RMC,
            User::ROLE_SUPERADMIN,
            User::ROLE_RND_COORDINATOR
        ])->get();

        return $roles;
    }
}
