<?php

namespace App\Policies;

use App\Helpers\CommentHelper;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class MonitoringEfPolicy
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
        return $user->can("monitoring-ef-list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->can("monitoring-ef-show");
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

        return $user->can("monitoring-ef-create")
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
            return $user->can("monitoring-ef-edit");
        }

        return $user->can("monitoring-ef-edit")
            && $user->id == $model->proposal->user_id
            && $model->approval_status == ReportQuarterly::STATUS_AMEND;
    }

    public function comment(User $user, ReportQuarterly $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("monitoring-ef-comment");
        }

        if (
            $model->approval_status != ReportQuarterly::STATUS_SUBMITED
            && $model->approval_status != ReportQuarterly::STATUS_RE_SUBMIT
        ) {
            return false;
        }

        if (!$user->can("monitoring-ef-comment")) {
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
            return $user->can("monitoring-ef-delete");
        }

        $approvementCount = $model->approvement->count();

        return $user->can("monitoring-ef-delete")
            && $user->id == $model->proposal->user_id
            && in_array($model->approval_status, [ReportQuarterly::STATUS_SUBMITED, ReportQuarterly::STATUS_DRAFT])
            && $approvementCount <= 0;
    }
}
