<?php

namespace App\Policies;

use App\Helpers\CommentHelper;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class MonitoringTrfPolicy
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
        return $user->can("monitoring-trf-list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function view(User $user, ReportQuarterly $model): bool
    {
        if ($user->hasRole(['Super Admin', 'Division Director', 'LKM Director', 'R&D Coordinator', 'RMC'])) {
            return $user->can("monitoring-trf-show");
        }

        return $user->can("monitoring-trf-show") && $user->id == $model->proposal->user_id;
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
            return $user->can("monitoring-trf-create");
        }

        return $user->can("monitoring-trf-create")
            && $user->id == $model->user_id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function update(User $user, ReportQuarterly $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("monitoring-trf-edit");
        }

        return $user->can("monitoring-trf-edit")
            && $user->id == $model->proposal->user_id
            && $model->approval_status == ReportQuarterly::STATUS_AMEND;
    }

    public function comment(User $user, ReportQuarterly $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("monitoring-trf-approval");
        }

        if (
            $model->approval_status != ReportQuarterly::STATUS_SUBMITED
            && $model->approval_status != ReportQuarterly::STATUS_RE_SUBMIT
        ) {
            return false;
        }

        if (!$user->can("monitoring-trf-approval")) {
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
    public function delete(User $user, ReportQuarterly $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("monitoring-trf-delete");
        }

        $approvementCount = $model->approvement->count();

        return $user->can("monitoring-trf-delete")
            && $user->id == $model->proposal->user_id
            && in_array($model->approval_status, [ReportQuarterly::STATUS_SUBMITED, ReportQuarterly::STATUS_DRAFT])
            && $approvementCount <= 0;
    }
}
