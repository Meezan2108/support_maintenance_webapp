<?php

namespace App\Helpers;

use App\Models\Approvement;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CommentHelper
{
    public static function determineRoleByStep($step)
    {
        // determine role by steps
        if ($step == 1) {
            $roleId = User::ROLE_RMC;
        } elseif ($step == 2) {
            $roleId = User::ROLE_LKM_DIRECTOR;
        } else {
            $roleId = User::ROLE_DIVISION_DIRECTOR;
        }

        return $roleId;
    }

    public static function checkPolicyByRole(Model $model, User $user)
    {
        if (
            (!$model->approvementStep
                || $model->approvementStep->step == 0)
            && $user->hasRole('Division Director')
        ) {
            $modelDivision = optional($model->researcher)->division
                ?? optional(optional($model->proposal)->researcher)->division;
            return (optional($modelDivision)->id == $user->ref_division_id);
        }

        if (
            optional($model->approvementStep)->step == 1
            && $user->hasRole('RMC')
        ) {
            return true;
        }

        if (
            optional($model->approvementStep)->step == 2
            && $user->hasRole('LKM Director')
        ) {
            return true;
        }

        return false;
    }

    public static function getOptionsStatus()
    {
        return [
            [
                "id" => "",
                "description" => "Choose Status"
            ],
            [
                "id" => Approvement::STATUS_APPROVED,
                "description" => "Approve"
            ],
            [
                "id" => Approvement::STATUS_AMEND,
                "description" => "Amend"
            ],
            [
                "id" => Approvement::STATUS_REJECTED,
                "description" => "Reject"
            ],
        ];
    }

    public static function getTopUserRole(User $user, Collection $listEvaluation)
    {
        // get user evaluation;
        $userEvaluation = $listEvaluation->where("evaluator_id", $user->id);

        if (
            $userEvaluation
            ->where("role_id", User::ROLE_LKM_DIRECTOR)
            ->isNotEmpty()
        ) {
            return User::ROLE_LKM_DIRECTOR;
        }

        if (
            $userEvaluation
            ->where("role_id", User::ROLE_RMC)
            ->isNotEmpty()
        ) {
            return User::ROLE_RMC;
        }

        if (
            $userEvaluation
            ->where("role_id", User::ROLE_DIVISION_DIRECTOR)
            ->isNotEmpty()
        ) {
            return User::ROLE_DIVISION_DIRECTOR;
        }
    }
}
