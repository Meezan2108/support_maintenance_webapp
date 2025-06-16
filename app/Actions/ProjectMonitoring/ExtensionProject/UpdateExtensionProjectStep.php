<?php

namespace App\Actions\ProjectMonitoring\ExtensionProject;

use App\Models\ExtensionProject;
use App\Models\User;

class UpdateExtensionProjectStep
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ExtensionProject $model, User $user)
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
