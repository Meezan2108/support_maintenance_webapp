<?php

namespace App\Actions\KpiMonitoring;

use App\Actions\Notification\CreateNotification;
use App\Jobs\SendEmailNotification;
use App\Models\Notifable;
use App\Models\Recognition;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateRecognitionSubmitNotification
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Recognition $recognition)
    {
        $options = [
            "link" => route("panel.recognition.show", ["recognition" => $recognition->id])
        ];

        (new CreateNotification)->execute(
            $recognition,
            Notifable::TARGET_TYPE_USER,
            Auth::id(),
            Template::TYPE_KPI_SUBMIT,
            $options
        );

        $options = [
            "link" => route("panel.recognition.approvement", ["recognition" => $recognition->id]),
        ];

        (new CreateNotification)->execute(
            $recognition,
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
