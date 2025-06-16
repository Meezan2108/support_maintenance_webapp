<?php

namespace App\Actions\ProjectMonitoring\ResearchProgressNoFund;

use App\Actions\Notification\CreateNotification;
use App\Helpers\CommentHelper;
use App\Jobs\SendEmailNotification;
use App\Jobs\SendEmailNotificationReport;
use App\Models\Approvement;
use App\Models\Notifable;
use App\Models\ReportResearchProgress;
use App\Models\Template;
use App\Models\User;

class CreateResearchProgressNoFundCommentNotification
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ReportResearchProgress $report, User $user, int $approvementStatus)
    {
        $showLink = route("panel.research-progress.show", ["report" => $report->id]);
        $editLink = route("panel.research-progress.edit", ["report" => $report->id]);

        $title = "Research Progress Report (No FUnd) : " . $report->project_title;

        $approvementStep = $report->approvementStep;
        $targetRoleId = CommentHelper::determineRoleByStep($approvementStep->step);
        $status = Approvement::formatStatus($report->approval_status);

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
                $report->division
            );

            // notif email to target group
            SendEmailNotificationReport::dispatch(
                "Research Progress Report (No Fund) has reviewed",
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
            (new CreateNotification)->execute($report, Notifable::TARGET_TYPE_USER, $report->user_id, Template::TYPE_REPORT_STATUS_UPDATED, $options);

            // notify email to researcher
            SendEmailNotification::dispatch(
                "Your Research Progress Report is " . $status,
                Notifable::TARGET_TYPE_USER,
                $report->user_id,
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
                $report->user_id,
                Template::TYPE_REPORT_REVIEWED,
                $options
            );

            // notify email to researcher
            SendEmailNotification::dispatch(
                "Your Research Progress Report has been reviewed",
                Notifable::TARGET_TYPE_USER,
                $report->user_id,
                Template::TYPE_REPORT_REVIEWED,
                $options
            );

            return;
        }

        if ($approvementStatus == Approvement::STATUS_APPROVED) {
            (new CreateNotification)->execute($report, Notifable::TARGET_TYPE_USER, $report->user_id, Template::TYPE_REPORT_REVIEWED, [
                "reviewer" => $user->name,
                "title" => $title,
                "link" => route("panel.research-progress.show", ["report" => $report->id])
            ]);

            $approvementStep = $report->approvementStep;

            $targetRoleId = CommentHelper::determineRoleByStep($approvementStep->step);

            $options = [
                "link" => route("panel.research-progress.comment", ["report" => $report->id])
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
                "Has Research Progress Report to review",
                Notifable::TARGET_TYPE_GROUP,
                $targetRoleId,
                Template::TYPE_REPORT_NEED_TO_REVIEW,
                $options
            );

            return;
        }
    }
}
