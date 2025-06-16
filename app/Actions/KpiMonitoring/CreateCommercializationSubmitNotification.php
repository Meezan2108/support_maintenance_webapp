<?php

namespace App\Actions\KpiMonitoring;

use App\Actions\Notification\CreateNotification;
use App\Jobs\SendEmailNotification;
use App\Models\Commercialization;
use App\Models\IPR;
use App\Models\Notifable;
use App\Models\OutputRnd;
use App\Models\Recognition;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateCommercializationSubmitNotification
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Commercialization $commercialization)
    {
        $options = [
            "link" => route("panel.commercialization.show", ["commercialization" => $commercialization->id])
        ];

        (new CreateNotification)->execute(
            $commercialization,
            Notifable::TARGET_TYPE_USER,
            Auth::id(),
            Template::TYPE_KPI_SUBMIT,
            $options
        );

        $options = [
            "link" => route("panel.commercialization.approvement", ["commercialization" => $commercialization->id]),
        ];

        (new CreateNotification)->execute(
            $commercialization,
            Notifable::TARGET_TYPE_GROUP,
            User::ROLE_RMC,
            Template::TYPE_KPI_NEED_TO_REVIEW,
            $options
        );

        // notif email to target group
        SendEmailNotification::dispatch(
            "Has KPI to review",
            Notifable::TARGET_TYPE_GROUP,
            User::ROLE_RMC,
            Template::TYPE_KPI_NEED_TO_REVIEW,
            $options
        );
    }
}
