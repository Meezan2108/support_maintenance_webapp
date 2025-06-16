<?php

namespace App\Policies;

use App\Helpers\CommentHelper;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class EvaluationPolicy
{
    use HandlesAuthorization;

    const ARR_STEP = [
        [
            "role_id" => User::ROLE_DIVISION_DIRECTOR,
            "code" => "division_director",
            "related_role" => [User::ROLE_DIVISION_DIRECTOR],
            "order" => 1,
        ],
        [
            "role_id" => User::ROLE_RMC,
            "code" => "rmc",
            "related_role" => [
                User::ROLE_DIVISION_DIRECTOR,
                User::ROLE_RMC
            ],
            "order" => 2
        ],
        [
            "role_id" => User::ROLE_LKM_DIRECTOR,
            "code" => "coordinator",
            "related_role" => [
                User::ROLE_DIVISION_DIRECTOR,
                User::ROLE_RMC,
                User::ROLE_LKM_DIRECTOR
            ],
            "order" => 3
        ],
    ];

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
        return $user->can("technical-evaluation-list");
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
        if (
            !$user->can("technical-evaluation-show")
            || !$model->evaluation()->exists()
        ) {
            return false;
        }

        if (
            $user->hasRole('Division Director')
            && !$user->hasRole(["RMC", "LKM Director", "R&D Coordinator", "Super Admin"])
            && !$model->evaluation()->where('evaluator_id', $user->id)->exists()
        ) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can("technical-evaluation-create");
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
            return $user->can("trf-comment");
        }

        if (
            $model->approval_status != Proposal::STATUS_SUBMITED
            && $model->approval_status != Proposal::STATUS_RE_SUBMIT
        ) {
            return false;
        }

        if (!$user->can("technical-evaluation-edit")) {
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
        return $user->can("technical-evaluation-delete");
    }
}
