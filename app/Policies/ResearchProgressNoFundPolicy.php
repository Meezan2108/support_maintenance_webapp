<?php

namespace App\Policies;

use App\Helpers\CommentHelper;
use App\Models\Proposal;
use App\Models\ReportResearchProgress;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class ResearchProgressNoFundPolicy
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
        return $user->can("research-progress-no-fund-list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function view(User $user, ReportResearchProgress $model): bool
    {
        if ($user->hasRole(['Super Admin', 'Division Director', 'LKM Director', 'R&D Coordinator', 'RMC'])) {
            return $user->can("research-progress-no-fund-show");
        }

        return $user->can("research-progress-no-fund-show") && $user->id == $model->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can("research-progress-no-fund-create");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function update(User $user, ReportResearchProgress $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("research-progress-no-fund-edit");
        }

        return $user->can("research-progress-no-fund-edit")
            && $user->id == $model->user_id
            && $model->approval_status == ReportResearchProgress::STATUS_AMEND;
    }

    public function comment(User $user, ReportResearchProgress $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("research-progress-no-fund-approval");
        }

        if (
            $model->approval_status != ReportResearchProgress::STATUS_SUBMITED
            && $model->approval_status != ReportResearchProgress::STATUS_RE_SUBMIT
        ) {
            return false;
        }

        if (!$user->can("research-progress-no-fund-approval")) {
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
    public function delete(User $user, ReportResearchProgress $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("research-progress-no-fund-delete");
        }

        $approvementCount = $model->approvement->count();

        return $user->can("research-progress-no-fund-delete")
            && $user->id == $model->user_id
            && $model->approval_status == ReportResearchProgress::STATUS_SUBMITED
            && $approvementCount <= 0;
    }
}
