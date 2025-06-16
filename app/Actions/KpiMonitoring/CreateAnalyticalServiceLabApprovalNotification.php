<?php

namespace App\Actions\KpiMonitoring;

use App\Actions\Notification\CreateNotification;
use App\Jobs\SendEmailNotification;
use App\Models\AnalyticalServiceLab;
use App\Models\Approvement;
use App\Models\Commercialization;
use App\Models\IPR;
use App\Models\Notifable;
use App\Models\OutputRnd;
use App\Models\Recognition;
use App\Models\Template;
use App\Models\User;

class CreateAnalyticalServiceLabApprovalNotification
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(AnalyticalServiceLab $kpi, User $user, int $approvementStatus)
    {
        $showLink = route("panel.analytical-service-lab.show", ["analytical" => $kpi->id]);
        $editLink = route("panel.analytical-service-lab.edit", ["analytical" => $kpi->id]);

        $title = "Analytical Service Lab : " . $kpi->date;
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
                "Your Analytical Service Lab is " . $status,
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
                "Your Analytical Service Lab has been reviewed",
                Notifable::TARGET_TYPE_USER,
                $kpi->user_id,
                Template::TYPE_KPI_REVIEWED,
                $options
            );
            return;
        }
    }
}
