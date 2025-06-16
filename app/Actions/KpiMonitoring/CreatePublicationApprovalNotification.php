<?php

namespace App\Actions\KpiMonitoring;

use App\Actions\Notification\CreateNotification;
use App\Jobs\SendEmailNotification;
use App\Models\Approvement;
use App\Models\Notifable;
use App\Models\Publication;
use App\Models\Template;
use App\Models\User;

class CreatePublicationApprovalNotification
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Publication $kpi, User $user, int $approvementStatus)
    {
        $showLink = route("panel.publications.show", ["publication" => $kpi->id]);
        $editLink = route("panel.publications.edit", ["publication" => $kpi->id]);

        $title = "Publication : " . $kpi->title;
        if (in_array($approvementStatus, [Approvement::STATUS_APPROVED, Approvement::STATUS_REJECTED])) {
            $status = Approvement::formatStatus($approvementStatus);
            $options = [
                "title" => $title,
                "status" => $status,
                "link" => $showLink
            ];

            (new CreateNotification)->execute(
                $kpi,
                Notifable::TARGET_TYPE_USER,
                $kpi->user_id,
                Template::TYPE_REPORT_STATUS_UPDATED,
                $options
            );

            // notify email to researcher
            SendEmailNotification::dispatch(
                "Your Publication is " . $status,
                Notifable::TARGET_TYPE_USER,
                $kpi->user_id,
                Template::TYPE_REPORT_STATUS_UPDATED,
                $options
            );

            return;
        }

        if ($approvementStatus == Approvement::STATUS_AMEND) {
            $options = [
                "reviewer" => $user->name,
                "title" => $title,
                "link" => $editLink
            ];

            (new CreateNotification)->execute(
                $kpi,
                Notifable::TARGET_TYPE_USER,
                $kpi->user_id,
                Template::TYPE_KPI_REVIEWED,
                $options
            );

            // notify email to researcher
            SendEmailNotification::dispatch(
                "Your Publication has been reviewed",
                Notifable::TARGET_TYPE_USER,
                $kpi->user_id,
                Template::TYPE_KPI_REVIEWED,
                $options
            );
            return;
        }
    }
}
