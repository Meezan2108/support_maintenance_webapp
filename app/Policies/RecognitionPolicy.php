<?php

namespace App\Policies;

use App\Models\Approvement;
use App\Models\Recognition;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class RecognitionPolicy
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
        return $user->can("recognition-list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function view(User $user, Recognition $model): bool
    {
        if ($user->hasRole(['Super Admin', 'Division Director', 'LKM Director', 'R&D Coordinator', 'RMC'])) {
            return $user->can("recognition-show");
        }

        return $user->can("recognition-show") && (
            $user->id == $model->user_id
            || $model->researcherInvolved()->where("user_id", $user->id)->exists()
        );
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can("recognition-create");
    }

    public function createBulk(User $user)
    {
        return $user->can("recognition-create-bulk");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function update(User $user, Recognition $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("recognition-edit");
        }

        return $user->can("recognition-edit")
            && $user->id == $model->user_id
            && $model->kpiAchievement->approval_status == Approvement::STATUS_AMEND;
    }

    /**
     * Determine whether the user can approve the model.
     *
     * @param  User  $user
     * @param  Recognition  $model
     * @return bool
     */
    public function approval(User $user, Recognition $model): bool
    {
        if (
            !in_array($model->kpiAchievement->approval_status, [
                Approvement::STATUS_RE_SUBMIT,
                Approvement::STATUS_SUBMITED
            ])
        ) {
            return false;
        }

        return $user->can('recognition-approval');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Recognition  $model
     * @return bool
     */
    public function delete(User $user, Recognition $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("recognition-delete")
                && $model->kpiAchievement->approval_status == Approvement::STATUS_SUBMITED;
        }

        return $user->can("recognition-delete")
            && $user->id == $model->user_id
            && $model->kpiAchievement->approval_status == Approvement::STATUS_SUBMITED;
    }
}
