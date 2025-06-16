<?php

namespace App\Actions;

use App\Models\ApprovementStep;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UpdateApprovementStep
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return ApprovementStep
     */
    public function execute(Model $model, User $user)
    {
        //which step ??
        $approvementStep = $model->approvementStep;
        if ($approvementStep) {
            if ($approvementStep->step >= 3) {
                return $approvementStep;
            }

            $step = $approvementStep->step + 1;
            $approvementStep->update([
                "step" => $step,
                "reviewer_$step" => $user->id
            ]);
        } else {
            $approvementStep = $model->approvementStep()
                ->create([
                    "step" => 1,
                    "reviewer_1" => $user->id
                ]);
        }

        return $approvementStep;
    }
}
