<?php

namespace App\Policies;

use App\Models\Approvement;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class PublicationPolicy
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
        return $user->can("publications-list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Publication  $model
     * @return bool
     */
    public function view(User $user, Publication $model): bool
    {
        if ($user->hasRole(['Super Admin', 'Division Director', 'LKM Director', 'R&D Coordinator', 'RMC'])) {
            return $user->can("publications-show");
        }

        return $user->can("publications-show") && (
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
        return $user->can("publications-create");
    }

    public function createBulk(User $user)
    {
        return $user->can("publications-create-bulk");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Publication  $model
     * @return bool
     */
    public function update(User $user, Publication $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("publications-edit");
        }

        return $user->can("publications-edit")
            && $user->id == $model->user_id
            && $model->kpiAchievement->approval_status == Approvement::STATUS_AMEND;
    }

    /**
     * Determine whether the user can approve the model.
     *
     * @param  User  $user
     * @param  Publication  $model
     * @return bool
     */
    public function approval(User $user, Publication $model): bool
    {
        if (
            !in_array($model->kpiAchievement->approval_status, [
                Approvement::STATUS_RE_SUBMIT,
                Approvement::STATUS_SUBMITED
            ])
        ) {
            return false;
        }

        return $user->can('publications-approval');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Publication  $model
     * @return bool
     */
    public function delete(User $user, Publication $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("publications-delete")
                && $model->kpiAchievement->approval_status == Approvement::STATUS_SUBMITED;
        }

        return $user->can("publications-delete")
            && $user->id == $model->user_id
            && $model->kpiAchievement->approval_status == Approvement::STATUS_SUBMITED;
    }
}
