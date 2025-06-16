<?php

namespace App\Policies;

use App\Models\Approvement;
use App\Models\Documentation;
use App\Models\Fileable;
use App\Models\Recognition;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class FileablePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user): bool
    {
        return $user->can("fileable-list");
    }
    
    public function view(User $user, Fileable $model): bool
    {
        return $user->can("fileable-show");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
}
