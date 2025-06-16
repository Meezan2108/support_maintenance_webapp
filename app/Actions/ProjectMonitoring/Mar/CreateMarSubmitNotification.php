<?php

namespace App\Actions\ProjectMonitoring\Mar;

use App\Actions\Notification\CreateNotification;
use App\Jobs\SendEmailNotification;
use App\Jobs\SendEmailNotificationReport;
use App\Models\Notifable;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateMarSubmitNotification
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ReportQuarterly $report, User $user)
    {
        (new CreateNotification)->execute($report, Notifable::TARGET_TYPE_USER, $user->id, Template::TYPE_REPORT_SUBMIT, [
            "link" => $report->proposal->proposal_type == Proposal::TYPE_TRF
                ? route("panel.monitoring-trf.mar.show", ["mar" => $report->id])
                : route("panel.monitoring-ef.mar.show", ["mar" => $report->id])
        ]);

        $linkComment = $report->proposal->proposal_type == Proposal::TYPE_TRF
            ? route("panel.monitoring-trf.mar.comment", ["mar" => $report->id])
            : route("panel.monitoring-ef.mar.comment", ["mar" => $report->id]);

        $options = [
            "link" => $linkComment
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
            "Has MAR to review",
            $notification->id
        );
    }
}
