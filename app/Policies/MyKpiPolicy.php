<?php

namespace App\Policies;

use App\Models\Approvement;
use App\Models\KpiAchievement;
use App\Models\OutputRnd;
use App\Models\Recognition;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class MyKpiPolicy
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
        return $user->can("my-kpi-list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function view(User $user, KpiAchievement $model): bool
    {
        if ($user->hasRole(['Super Admin', 'Division Director', 'LKM Director', 'R&D Coordinator', 'RMC'])) {
            return $user->can("my-kpi-show");
        }

        $isResearcher = $model->researcher()->where("users.id", $user->id)->exists();
        return $user->can("my-kpi-show") && ($user->id == $model->user_id || $isResearcher);
    }

    public function update(User $user, KpiAchievement $model): bool
    {
        if ($user->hasRole(['Super Admin'])) {
            return $user->can("my-kpi-edit");
        }

        return $user->can("my-kpi-edit")
            && $user->id == $model->user_id
            && $model->approval_status == Approvement::STATUS_AMEND;
    }
}
