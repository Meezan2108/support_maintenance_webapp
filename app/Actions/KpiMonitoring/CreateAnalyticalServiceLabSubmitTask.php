<?php

namespace App\Actions\KpiMonitoring;

use App\Actions\CreateTask;
use App\Models\AnalyticalServiceLab;
use App\Models\Commercialization;
use App\Models\IPR;
use App\Models\OutputRnd;
use App\Models\Recognition;
use App\Models\Taskable;
use App\Models\User;

class CreateAnalyticalServiceLabSubmitTask
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(AnalyticalServiceLab $analytical, User $createdByUser)
    {
        return (new CreateTask)->execute(
            $analytical,
            $createdByUser,
            User::ROLE_RMC,
            Taskable::TARGET_TYPE_GROUP,
            route("panel.analytical-service-lab.approvement", ["analytical" => $analytical->id]),
            "-",
            $analytical->date,
            'KPI Analytical Service Lab',
            $analytical->kpiAchievement->approval_status
        );
    }
}
