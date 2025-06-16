<?php

namespace App\Actions\ProjectMonitoring;

use App\Models\ReportQuarterly;
use App\Models\User;
use Carbon\Carbon;

class UpdateReportQuarterlyStep
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ReportQuarterly $model, User $user)
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
