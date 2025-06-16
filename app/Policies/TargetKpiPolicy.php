<?php

namespace App\Policies;

use App\Models\Approvement;
use App\Models\TargetKpi;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TargetKpiPolicy
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
        return $user->can("target-kpi-list");
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
        if ($user->hasRole(['Super Admin', 'Division Director', 'LKM Director', 'R&D Coordinator', 'RMC'])) {
            return $user->can("target-kpi-show");
        }

        return $user->can("target-kpi-show");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can("target-kpi-create");
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
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("target-kpi-edit");
        }

        return $user->can("target-kpi-edit");
    }

    /**
     * Determine whether the user can approve the model.
     *
     * @param  User  $user
     * @param  TargetKpi  $model
     * @return bool
     */
    public function approval(User $user, TargetKpi $model): bool
    {
        if (!in_array($model->approval_status, [
            Approvement::STATUS_RE_SUBMIT, Approvement::STATUS_SUBMITED
        ])) {
            return false;
        }

        return $user->can('target-kpi-approval');
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
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("target-kpi-delete");
        }

        return $user->can("target-kpi-delete");
    }
}
