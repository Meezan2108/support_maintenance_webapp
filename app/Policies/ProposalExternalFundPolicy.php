<?php

namespace App\Policies;

use App\Helpers\CommentHelper;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class ProposalExternalFundPolicy
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
        return $user->can("external-fund-list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function view(User $user, Proposal $model): bool
    {
        if ($user->hasRole(['Super Admin', 'Division Director', 'LKM Director', 'R&D Coordinator', 'RMC'])) {
            return $user->can("external-fund-show");
        }

        return $user->can("external-fund-show") && $user->id == $model->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can("external-fund-create");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function update(User $user, Proposal $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("external-fund-edit");
        }

        return $user->can("external-fund-edit")
            && (
                (
                    $user->id == $model->user_id
                    && in_array($model->approval_status, [Approvement::STATUS_AMEND, Approvement::STATUS_DRAFT])
                    && $model->is_by_rmc == 0
                )
                || ($user->hasRole("RMC") && $model->is_by_rmc)
            );
    }

    public function comment(User $user, Proposal $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("external-fund-comment");
        }

        if (
            $model->approval_status != Approvement::STATUS_SUBMITED
            && $model->approval_status != Approvement::STATUS_RE_SUBMIT
        ) {
            return false;
        }

        if (!$user->can("external-fund-comment")) {
            return false;
        }

        return CommentHelper::checkPolicyByRole($model, $user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function delete(User $user, Proposal $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("external-fund-delete");
        }

        $approvementCount = $model->approvement->count();

        return $user->can("external-fund-delete")
            && $user->id == $model->user_id
            && $model->approval_status == Approvement::STATUS_SUBMITED
            && $approvementCount <= 0;
    }
}
