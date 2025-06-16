<?php

namespace App\Actions\KpiMonitoring;

use App\Actions\CreateTask;
use App\Models\OutputRnd;
use App\Models\Recognition;
use App\Models\Taskable;
use App\Models\User;

class CreateOutputRndSubmitTask
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(OutputRnd $outputrnd, User $createdByUser)
    {
        (new CreateTask)->execute(
            $outputrnd,
            $createdByUser,
            User::ROLE_RMC,
            Taskable::TARGET_TYPE_GROUP,
            route("panel.rnd-output.approvement", ["outputrnd" => $outputrnd->id]),
            "-",
            $outputrnd->output,
            'KPI R&D Output',
            $outputrnd->kpiAchievement->approval_status
        );
    }
}
