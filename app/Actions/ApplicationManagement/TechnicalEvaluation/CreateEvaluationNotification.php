<?php

namespace App\Actions\ApplicationManagement\TechnicalEvaluation;

use App\Actions\Notification\CreateNotification;
use App\Helpers\CommentHelper;
use App\Jobs\SendEmailNotification;
use App\Jobs\SendEmailNotificationProposal;
use App\Models\Approvement;
use App\Models\Notifable;
use App\Models\Proposal;
use App\Models\Template;
use App\Models\User;
use Carbon\Carbon;

class CreateEvaluationNotification
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Proposal $proposal, User $user, int $approvementStatus)
    {
        $showLink = $proposal->proposal_type == Proposal::TYPE_TRF
            ? route("panel.trf.show", ["proposal" => $proposal->id])
            : route("panel.external-fund.show", ["proposal" => $proposal->id]);

        $editLink = $proposal->proposal_type == Proposal::TYPE_TRF
            ? route("panel.trf.edit", ["proposal" => $proposal->id])
            : route("panel.external-fund.edit", ["proposal" => $proposal->id]);

        $evaluationLink = route("panel.technical-evaluation.edit", ["proposal" => $proposal->id]);

        $approvementStep = $proposal->approvementStep;
        $targetRoleId = CommentHelper::determineRoleByStep($approvementStep->step);
        $status = $proposal->formatStatus();

        if ($targetRoleId != User::ROLE_DIVISION_DIRECTOR) {
            $options = [
                "title" => $proposal->project_title,
                "status" => Approvement::formatStatus($approvementStatus),
                "link" => $showLink
            ];

            $notification = (new CreateNotification)->execute(
                $proposal,
                Notifable::TARGET_TYPE_GROUP,
                User::ROLE_DIVISION_DIRECTOR,
                Template::TYPE_PROPOSAL_DIVISION_INFO,
                $options,
                $proposal->researcher->division
            );

            // notif email to target group
            SendEmailNotificationProposal::dispatch(
                "Your Proposal has been reviewed",
                $notification->id
            );
        }

        if (in_array($proposal->approval_status, [Approvement::STATUS_APPROVED, Approvement::STATUS_REJECTED])) {

            $options = [
                "title" => $proposal->project_title,
                "status" => $status,
                "link" => $showLink
            ];

            (new CreateNotification)->execute(
                $proposal,
                Notifable::TARGET_TYPE_USER,
                $proposal->user_id,
                Template::TYPE_PROPOSAL_STATUS_UPDATED,
                $options
            );

            // notify email to researcher
            SendEmailNotification::dispatch(
                "Your Proposal is " . $status,
                Notifable::TARGET_TYPE_USER,
                $proposal->user_id,
                Template::TYPE_PROPOSAL_STATUS_UPDATED,
                $options
            );

            return;
        }

        if ($approvementStatus == Approvement::STATUS_AMEND) {
            $options = [
                "reviewer" => $user->name,
                "title" => $proposal->project_title,
                "link" => $showLink
            ];

            (new CreateNotification)->execute(
                $proposal,
                Notifable::TARGET_TYPE_USER,
                $proposal->user_id,
                Template::TYPE_PROPOSAL_REVIEWED,
                $options
            );

            // notify email to researcher
            SendEmailNotification::dispatch(
                "Your Proposal has been reviewed",
                Notifable::TARGET_TYPE_USER,
                $proposal->user_id,
                Template::TYPE_PROPOSAL_REVIEWED,
                $options
            );

            return;
        }

        if ($approvementStatus == Approvement::STATUS_APPROVED) {
            (new CreateNotification)->execute($proposal, Notifable::TARGET_TYPE_USER, $proposal->user_id, Template::TYPE_PROPOSAL_REVIEWED, [
                "reviewer" => $user->name,
                "title" => $proposal->project_title,
                "link" => $showLink
            ]);

            $options = [
                "link" => $evaluationLink,
            ];

            $notification = (new CreateNotification)->execute(
                $proposal,
                Notifable::TARGET_TYPE_GROUP,
                $targetRoleId,
                Template::TYPE_PROPOSAL_NEED_TO_REVIEW,
                $options,
                $proposal->researcher->division
            );

            // notif email to target group
            SendEmailNotificationProposal::dispatch(
                "Has Proposal to review",
                $notification->id
            );

            return;
        }
    }
}
