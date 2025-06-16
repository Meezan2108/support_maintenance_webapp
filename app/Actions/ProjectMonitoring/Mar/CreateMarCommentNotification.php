<?php

namespace App\Actions\ProjectMonitoring\Mar;

use App\Actions\Notification\CreateNotification;
use App\Helpers\CommentHelper;
use App\Jobs\SendEmailNotification;
use App\Jobs\SendEmailNotificationReport;
use App\Models\Approvement;
use App\Models\Notifable;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Models\Template;
use App\Models\User;

class CreateMarCommentNotification
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ReportQuarterly $report, User $user, int $approvementStatus)
    {
        $showLink = $report->proposal->proposal_type == Proposal::TYPE_TRF
            ? route("panel.monitoring-trf.mar.show", ["mar" => $report->id])
            : route("panel.monitoring-ef.mar.show", ["mar" => $report]);

        $editLink = $report->proposal->proposal_type == Proposal::TYPE_TRF
            ? route("panel.monitoring-trf.mar.edit", ["mar" => $report->id])
            : route("panel.monitoring-ef.mar.edit", ["mar" => $report->id]);

        $title = "MAR : " . $report->proposal->project_title;

        $approvementStep = $report->approvementStep;
        $targetRoleId = CommentHelper::determineRoleByStep($approvementStep->step);


        if ($targetRoleId != User::ROLE_DIVISION_DIRECTOR) {
            $options = [
                "title" => $title,
                "status" => Approvement::formatStatus($approvementStatus),
                "link" => $showLink
            ];

            $notification = (new CreateNotification)->execute(
                $report,
                Notifable::TARGET_TYPE_GROUP,
                User::ROLE_DIVISION_DIRECTOR,
                Template::TYPE_REPORT_DIVISION_INFO,
                $options,
                $report->proposal->researcher->division
            );

            // notif email to target group
            SendEmailNotificationReport::dispatch(
                "Milestone Achievement Report has reviewed",
                $notification->id
            );
        }
        if (in_array($report->approval_status, [Approvement::STATUS_APPROVED, Approvement::STATUS_REJECTED])) {
            $status = Approvement::formatStatus($report->approval_status);
            $options = [
                "title" => $title,
                "status" => $status,
                "link" => $showLink
            ];

            (new CreateNotification)->execute(
                $report,
                Notifable::TARGET_TYPE_USER,
                $report->proposal->user_id,
                Template::TYPE_REPORT_STATUS_UPDATED,
                $options
            );

            // notify email to researcher
            SendEmailNotification::dispatch(
                "Your MAR is " . $status,
                Notifable::TARGET_TYPE_USER,
                $report->proposal->user_id,
                Template::TYPE_REPORT_STATUS_UPDATED,
                $options
            );

            return;
        }

        if ($approvementStatus == Approvement::STATUS_AMEND) {
            $options = [
                "reviewer" => $user->name,
                "title" => $title,
                "link" => $showLink
            ];

            (new CreateNotification)->execute(
                $report,
                Notifable::TARGET_TYPE_USER,
                $report->proposal->user_id,
                Template::TYPE_REPORT_REVIEWED,
                $options
            );

            // notify email to researcher
            SendEmailNotification::dispatch(
                "Your MAR has been reviewed",
                Notifable::TARGET_TYPE_USER,
                $report->proposal->user_id,
                Template::TYPE_REPORT_REVIEWED,
                $options
            );

            return;
        }

        if ($approvementStatus == Approvement::STATUS_APPROVED) {
            (new CreateNotification)->execute($report, Notifable::TARGET_TYPE_USER, $report->proposal->user_id, Template::TYPE_REPORT_REVIEWED, [
                "reviewer" => $user->name,
                "title" => $title,
                "link" => $showLink
            ]);

            $approvementStep = $report->approvementStep;

            $targetRoleId = CommentHelper::determineRoleByStep($approvementStep->step);

            $options = [
                "link" => $report->proposal->proposal_type == Proposal::TYPE_TRF
                    ? route("panel.monitoring-trf.mar.comment", ["mar" => $report->id])
                    : route("panel.monitoring-ef.mar.comment", ["mar" => $report->id])
            ];

            (new CreateNotification)->execute(
                $report,
                Notifable::TARGET_TYPE_GROUP,
                $targetRoleId,
                Template::TYPE_REPORT_NEED_TO_REVIEW,
                $options
            );

            // notif email to target group
            SendEmailNotification::dispatch(
                "Has MAR to review",
                Notifable::TARGET_TYPE_GROUP,
                $targetRoleId,
                Template::TYPE_REPORT_NEED_TO_REVIEW,
                $options
            );

            return;
        }
    }
}
