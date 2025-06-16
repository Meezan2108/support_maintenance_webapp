<?php

namespace App\Policies;

use App\Models\Approvement;
use App\Models\OutputRnd;
use App\Models\Recognition;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class OutputRnDPolicy
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
        return $user->can("rnd-output-list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function view(User $user, OutputRnd $model): bool
    {
        if ($user->hasRole(['Super Admin', 'Division Director', 'LKM Director', 'R&D Coordinator', 'RMC'])) {
            return $user->can("rnd-output-show");
        }

        return $user->can("rnd-output-show") && $user->id == $model->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can("rnd-output-create");
    }

    public function createBulk(User $user)
    {
        return $user->can("rnd-output-create-bulk");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function update(User $user, OutputRnd $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("rnd-output-edit");
        }

        return $user->can("rnd-output-edit")
            && $user->id == $model->user_id
            && $model->kpiAchievement->approval_status == Approvement::STATUS_AMEND;
    }

    public function approval(User $user, OutputRnd $model): bool
    {
        if (
            !in_array($model->kpiAchievement->approval_status, [
                Approvement::STATUS_RE_SUBMIT,
                Approvement::STATUS_SUBMITED
            ])
        ) {
            return false;
        }

        return $user->can('rnd-output-approval');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function delete(User $user, OutputRnd $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("rnd-output-delete")
                && $model->kpiAchievement->approval_status == Approvement::STATUS_SUBMITED;
        }

        return $user->can("rnd-output-delete")
            && $user->id == $model->user_id
            && $model->kpiAchievement->approval_status == Approvement::STATUS_SUBMITED;
    }
}
