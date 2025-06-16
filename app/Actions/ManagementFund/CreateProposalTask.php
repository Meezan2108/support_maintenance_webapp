<?php

namespace App\Actions\ManagementFund;

use App\Actions\CreateTask;
use App\Actions\Notification\CreateNotification;
use App\Models\Notifable;
use App\Models\Proposal;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateProposalTask
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Proposal $proposal, User $createdByUser)
    {
        (new CreateTask)->execute(
            $proposal,
            $createdByUser,
            User::ROLE_DIVISION_DIRECTOR,
            "group",
            route("panel.technical-evaluation.edit", ["proposal" => $proposal->id]),
            $proposal->application_id,
            $proposal->project_title,
            $proposal->proposal_type == Proposal::TYPE_TRF
                ? "TRF"
                : "External Fund",
            $proposal->approval_status,
            optional($proposal->researcher)->division
        );
    }
}
