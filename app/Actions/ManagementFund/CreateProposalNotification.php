<?php

namespace App\Actions\ManagementFund;

use App\Actions\Notification\CreateNotification;
use App\Jobs\SendEmailNotification;
use App\Jobs\SendEmailNotificationProposal;
use App\Models\Notifable;
use App\Models\Proposal;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateProposalNotification
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Proposal $proposal)
    {
        (new CreateNotification)->execute($proposal, Notifable::TARGET_TYPE_USER, Auth::id(), Template::TYPE_PROPOSAL_SUBMIT, [
            "link" => $proposal->proposal_type == Proposal::TYPE_TRF
                ? route("panel.trf.show", ["proposal" => $proposal->id])
                : route("panel.external-fund.show", ["proposal" => $proposal->id])
        ]);

        $options = [
            "link" => route("panel.technical-evaluation.edit", ["proposal" => $proposal->id]),
        ];

        $notification = (new CreateNotification)->execute(
            $proposal,
            Notifable::TARGET_TYPE_GROUP,
            User::ROLE_DIVISION_DIRECTOR,
            Template::TYPE_PROPOSAL_NEED_TO_REVIEW,
            $options,
            optional($proposal->researcher)->division
        );

        // notif email to target group
        SendEmailNotificationProposal::dispatch(
            "Has Proposal to review",
            $notification->id
        );
    }
}
