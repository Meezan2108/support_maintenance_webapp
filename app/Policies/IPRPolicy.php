<?php

namespace App\Policies;

use App\Models\Approvement;
use App\Models\IPR;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class IPRPolicy
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
        return $user->can("ipr-list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function view(User $user, IPR $model): bool
    {
        if ($user->hasRole(['Super Admin', 'Division Director', 'LKM Director', 'R&D Coordinator', 'RMC'])) {
            return $user->can("ipr-show");
        }

        return $user->can("ipr-show") && $user->id == $model->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can("ipr-create");
    }

    public function createBulk(User $user)
    {
        return $user->can("ipr-create-bulk");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function update(User $user, IPR $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("ipr-edit");
        }

        return $user->can("ipr-edit")
            && $user->id == $model->user_id
            && $model->kpiAchievement->approval_status == Approvement::STATUS_AMEND;
    }

    public function approval(User $user, IPR $model): bool
    {
        if (
            !in_array($model->kpiAchievement->approval_status, [
                Approvement::STATUS_RE_SUBMIT,
                Approvement::STATUS_SUBMITED
            ])
        ) {
            return false;
        }

        return $user->can('ipr-approval');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function delete(User $user, IPR $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("ipr-delete")
                && $model->kpiAchievement->approval_status == Approvement::STATUS_SUBMITED;
        }

        return $user->can("ipr-delete")
            && $user->id == $model->user_id
            && $model->kpiAchievement->approval_status == Approvement::STATUS_SUBMITED;
    }
}
