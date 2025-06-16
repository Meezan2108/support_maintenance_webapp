<?php

namespace App\Policies;

use App\Models\Approvement;
use App\Models\TargetKpi;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TargetKpiGlobalPolicy
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
    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can("target-kpi-global-list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  TargetKpi  $model
     * @return bool
     */
    public function view(User $user, TargetKpi $model): bool
    {

        return $user->can("target-kpi-global-show");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can("target-kpi-global-create");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  TargetKpi  $model
     * @return bool
     */
    public function update(User $user, TargetKpi $model): bool
    {
        return $user->can("target-kpi-global-edit");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  TargetKpi  $model
     * @return bool
     */
    public function delete(User $user, TargetKpi $model): bool
    {
        return $user->can("target-kpi-global-delete");
    }
}
