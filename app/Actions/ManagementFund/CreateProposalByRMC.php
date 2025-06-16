<?php

namespace App\Actions\ManagementFund;

use App\Actions\CreateTask;
use App\Models\Proposal;
use App\Models\User;

class CreateProposalByRMC
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Proposal $proposal, User $createdByUser)
    {
        $arrStep = [
            "step" => 3,
            "reviewer_1" => $createdByUser->id,
            "reviewer_2" => $createdByUser->id,
            "reviewer_3" => $createdByUser->id
        ];

        $approvementStep = $proposal->approvementStep;
        if ($approvementStep) {
            $approvementStep->update($arrStep);
        } else {
            $proposal->approvementStep()
                ->create($arrStep);
        }

        $proposal->project_status = Proposal::STATUS_PRJ_ON_GOING;

        $proposal->save();
    }
}
