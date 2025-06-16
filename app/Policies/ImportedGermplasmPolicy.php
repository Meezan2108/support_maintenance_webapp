<?php

namespace App\Policies;

use App\Models\Approvement;
use App\Models\ImportedGermplasm;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class ImportedGermplasmPolicy
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
        return $user->can("imported-germplasm-list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function view(User $user, ImportedGermplasm $model): bool
    {
        if ($user->hasRole(['Super Admin', 'Division Director', 'LKM Director', 'R&D Coordinator', 'RMC'])) {
            return $user->can("imported-germplasm-show");
        }

        return $user->can("imported-germplasm-show") && $user->id == $model->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can("imported-germplasm-create");
    }

    public function createBulk(User $user)
    {
        return $user->can("imported-germplasm-create-bulk");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function update(User $user, ImportedGermplasm $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("imported-germplasm-edit");
        }

        return $user->can("imported-germplasm-edit")
            && $user->id == $model->user_id
            && $model->kpiAchievement->approval_status == Approvement::STATUS_AMEND;
    }

    public function approval(User $user, ImportedGermplasm $model): bool
    {
        if (
            !in_array($model->kpiAchievement->approval_status, [
                Approvement::STATUS_RE_SUBMIT,
                Approvement::STATUS_SUBMITED
            ])
        ) {
            return false;
        }

        return $user->can('imported-germplasm-approval');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function delete(User $user, ImportedGermplasm $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("imported-germplasm-delete")
                && $model->kpiAchievement->approval_status == Approvement::STATUS_SUBMITED;
        }

        return $user->can("imported-germplasm-delete")
            && $user->id == $model->user_id
            && $model->kpiAchievement->approval_status == Approvement::STATUS_SUBMITED;
    }

}
