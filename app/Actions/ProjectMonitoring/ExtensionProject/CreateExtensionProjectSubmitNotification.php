<?php

namespace App\Actions\ProjectMonitoring\ExtensionProject;

use App\Actions\Notification\CreateNotification;
use App\Jobs\SendEmailNotification;
use App\Jobs\SendEmailNotificationReport;
use App\Models\ExtensionProject;
use App\Models\Notifable;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateExtensionProjectSubmitNotification
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ExtensionProject $report, User $user)
    {
        (new CreateNotification)->execute($report, Notifable::TARGET_TYPE_USER, $user->id, Template::TYPE_REPORT_SUBMIT, [
            "link" => route("panel.extension-project.show", ["application" => $report->id])
        ]);

        $options = [
            "link" => route("panel.extension-project.comment", ["application" => $report->id])
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
            "Has Extension of Project Application to review",
            $notification->id
        );
    }
}
