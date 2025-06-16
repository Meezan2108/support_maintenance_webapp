<?php

namespace App\Actions\ProjectMonitoring\ResearchProgress;

use App\Actions\Notification\CreateNotification;
use App\Jobs\SendEmailNotification;
use App\Jobs\SendEmailNotificationReport;
use App\Models\Notifable;
use App\Models\ReportResearchProgress;
use App\Models\Template;
use App\Models\User;

class CreateResearchProgressSubmitNotification
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ReportResearchProgress $report, User $user)
    {
        (new CreateNotification)->execute($report, Notifable::TARGET_TYPE_USER, $user->id, Template::TYPE_REPORT_SUBMIT, [
            "link" => route("panel.research-progress.show", ["report" => $report->id])
        ]);

        $options = [
            "link" => route("panel.research-progress.comment", ["report" => $report->id])
        ];

        $notification = (new CreateNotification)->execute(
            $report,
            Notifable::TARGET_TYPE_GROUP,
            User::ROLE_DIVISION_DIRECTOR,
            Template::TYPE_REPORT_NEED_TO_REVIEW,
            $options,
            $report->proposal->researcher->division
        );

        // notif email to target group
        SendEmailNotificationReport::dispatch(
            "Has Research Progress Report to review",
            $notification->id
        );
    }
}
