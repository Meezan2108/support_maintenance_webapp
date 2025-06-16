<?php

namespace App\Actions\KpiMonitoring;

use App\Actions\CreateTask;
use App\Models\AnalyticalServiceLab;
use App\Models\Commercialization;
use App\Models\ImportedGermplasm;
use App\Models\IPR;
use App\Models\OutputRnd;
use App\Models\Recognition;
use App\Models\Taskable;
use App\Models\User;
use Carbon\Carbon;

class CreateImportedGermplasmSubmitTask
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ImportedGermplasm $germplasm, User $createdByUser)
    {
        (new CreateTask)->execute(
            $germplasm,
            $createdByUser,
            User::ROLE_RMC,
            Taskable::TARGET_TYPE_GROUP,
            route("panel.imported-germplasm.approvement", ["germplasm" => $germplasm->id]),
            "-",
            Carbon::parse($germplasm->date)->format("Y-m-d"),
            'KPI Imported Germplasm',
            $germplasm->kpiAchievement->approval_status
        );
    }
}
