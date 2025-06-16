<?php

namespace App\Actions\KpiMonitoring;

use App\Actions\CreateTask;
use App\Models\Commercialization;
use App\Models\IPR;
use App\Models\OutputRnd;
use App\Models\Recognition;
use App\Models\Taskable;
use App\Models\User;

class CreateCommercializationSubmitTask
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Commercialization $commercialization, User $createdByUser)
    {
        (new CreateTask)->execute(
            $commercialization,
            $createdByUser,
            User::ROLE_RMC,
            Taskable::TARGET_TYPE_GROUP,
            route("panel.commercialization.approvement", ["commercialization" => $commercialization->id]),
            "-",
            $commercialization->name,
            'KPI Commercialization',
            $commercialization->kpiAchievement->approval_status
        );
    }
}
