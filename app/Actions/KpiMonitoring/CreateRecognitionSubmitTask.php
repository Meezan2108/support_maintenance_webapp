<?php

namespace App\Actions\KpiMonitoring;

use App\Actions\CreateTask;
use App\Models\Recognition;
use App\Models\Taskable;
use App\Models\User;

class CreateRecognitionSubmitTask
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Recognition $recognition, User $createdByUser)
    {
        (new CreateTask)->execute(
            $recognition,
            $createdByUser,
            User::ROLE_RMC,
            Taskable::TARGET_TYPE_GROUP,
            route("panel.recognition.approvement", ["recognition" => $recognition->id]),
            "-",
            $recognition->recognition,
            'KPI Recognition',
            $recognition->kpiAchievement->approval_status
        );
    }
}
