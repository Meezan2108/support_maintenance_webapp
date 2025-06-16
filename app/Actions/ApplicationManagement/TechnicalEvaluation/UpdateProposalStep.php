<?php

namespace App\Actions\ApplicationManagement\TechnicalEvaluation;

use App\Models\ApprovementStep;
use App\Models\Proposal;
use App\Models\User;
use Carbon\Carbon;

class UpdateProposalStep
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return ApprovementStep
     */
    public function execute(Proposal $proposal, User $user)
    {
        //which step ??
        $approvementStep = $proposal->approvementStep;
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
            $approvementStep = $proposal->approvementStep()
                ->create([
                    "step" => 1,
                    "reviewer_1" => $user->id
                ]);
        }

        return $approvementStep;
    }
}
