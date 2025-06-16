<?php

namespace App\Actions\ProjectMonitoring\EndOfProject;

use App\Actions\Notification\CreateNotification;
use App\Helpers\CommentHelper;
use App\Jobs\SendEmailNotification;
use App\Jobs\SendEmailNotificationReport;
use App\Models\Approvement;
use App\Models\Notifable;
use App\Models\ReportEndProject;
use App\Models\Template;
use App\Models\User;

class CreateEndProjectCommentNotification
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ReportEndProject $report, User $user, int $approvementStatus)
    {
        $showLink = route("panel.end-of-project.show", ["report" => $report]);
        $editLink = route("panel.end-of-project.edit", ["report" => $report->id]);
        $comentLink = route("panel.end-of-project.comment", ["report" => $report->id]);

        $title = "End Of Project : " . $report->proposal->project_title;

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
                $report->proposal->researcher->division
            );

            // notif email to target group
            SendEmailNotificationReport::dispatch(
                "End of Project has reviewed",
                $notification->id
            );
        }

        if (in_array($report->approval_status, [Approvement::STATUS_APPROVED, Approvement::STATUS_REJECTED])) {

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
                "Your End Of Project Report is " . $status,
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
                "Your End of Project Report has been reviewed",
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
                "link" => $comentLink
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
                "Has End of Project Report to review",
                Notifable::TARGET_TYPE_GROUP,
                $targetRoleId,
                Template::TYPE_REPORT_NEED_TO_REVIEW,
                $options
            );

            return;
        }
    }
}
