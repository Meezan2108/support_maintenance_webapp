<?php

namespace App\Actions\KpiMonitoring;

use App\Actions\CreateTask;
use App\Models\IPR;
use App\Models\OutputRnd;
use App\Models\Recognition;
use App\Models\Taskable;
use App\Models\User;

class CreateIPRSubmitTask
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(IPR $ipr, User $createdByUser)
    {
        (new CreateTask)->execute(
            $ipr,
            $createdByUser,
            User::ROLE_RMC,
            Taskable::TARGET_TYPE_GROUP,
            route("panel.ipr.approvement", ["ipr" => $ipr->id]),
            "-",
            $ipr->output,
            'KPI IPR',
            $ipr->kpiAchievement->approval_status
        );
    }
}
