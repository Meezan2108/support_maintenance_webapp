<?php

namespace App\Policies;

use App\Helpers\CommentHelper;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ReportEndProject;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class EndOfProjectPolicy
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
        return $user->can("end-of-project-list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function view(User $user, ReportEndProject $model): bool
    {
        if ($user->hasRole(['Super Admin', 'Division Director', 'LKM Director', 'R&D Coordinator', 'RMC'])) {
            return $user->can("end-of-project-show");
        }

        return $user->can("end-of-project-show") && $user->id == $model->proposal->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user, Proposal $model = null): bool
    {
        if ($user->hasRole(['Super Admin']) || !$model) {
            return $user->can("end-of-project-create");
        }

        return $user->can("end-of-project-create")
            && $user->id == $model->user_id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function update(User $user, ReportEndProject $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("end-of-project-edit");
        }

        return $user->can("end-of-project-edit")
            && $user->id == $model->proposal->user_id
            && $model->approval_status == Approvement::STATUS_AMEND;
    }

    public function comment(User $user, ReportEndProject $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("end-of-project-approval");
        }

        if (
            $model->approval_status != Approvement::STATUS_SUBMITED
            && $model->approval_status != Approvement::STATUS_RE_SUBMIT
        ) {
            return false;
        }

        if (!$user->can("end-of-project-approval")) {
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
    public function delete(User $user, ReportEndProject $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("end-of-project-delete");
        }

        $approvementCount = $model->approvement->count();

        return $user->can("end-of-project-delete")
            && $user->id == $model->proposal->user_id
            && $model->approval_status == Approvement::STATUS_SUBMITED
            && $approvementCount <= 0;
    }
}
