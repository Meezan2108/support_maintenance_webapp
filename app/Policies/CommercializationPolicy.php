<?php

namespace App\Policies;

use App\Models\Approvement;
use App\Models\Commercialization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class CommercializationPolicy
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
        return $user->can("commercialization-list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function view(User $user, Commercialization $model): bool
    {
        if ($user->hasRole(['Super Admin', 'Division Director', 'LKM Director', 'R&D Coordinator', 'RMC'])) {
            return $user->can("commercialization-show");
        }

        return $user->can("commercialization-show") && $user->id == $model->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can("commercialization-create");
    }

    public function createBulk(User $user)
    {
        return $user->can("commercialization-create-bulk");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function update(User $user, Commercialization $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("commercialization-edit");
        }

        return $user->can("commercialization-edit")
            && $user->id == $model->user_id
            && $model->kpiAchievement->approval_status == Approvement::STATUS_AMEND;
    }

    public function approval(User $user, Commercialization $model): bool
    {
        if (
            !in_array($model->kpiAchievement->approval_status, [
                Approvement::STATUS_RE_SUBMIT,
                Approvement::STATUS_SUBMITED
            ])
        ) {
            return false;
        }

        return $user->can('commercialization-approval');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function delete(User $user, Commercialization $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("commercialization-delete")
                && $model->kpiAchievement->approval_status == Approvement::STATUS_SUBMITED;
        }

        return $user->can("commercialization-delete")
            && $user->id == $model->user_id
            && $model->kpiAchievement->approval_status == Approvement::STATUS_SUBMITED;
    }
}
