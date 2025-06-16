<?php

namespace App\Actions\KpiMonitoring;

use App\Helpers\DateHelper;
use App\Models\Approvement;
use App\Models\ImportedGermplasm;
use App\Models\User;

class CreateImportedGermplasmBulkData
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return void
     */
    public function execute($arrData = [], $userAuth = null)
    {
        foreach ($arrData as $data) {

            $user = User::where('email', $data["project_leader"])
                ->first();

            $kpiItem = ImportedGermplasm::create([
                "user_id" => $user->id,
                "date" => DateHelper::calcDateByQuarter($data['year'], $data['quarter']),
                "year" => $data["year"],
                "quarter" => $data["quarter"],
                "no_germplasm" => $data["no_of_germplasm"],
                "created_by" => $userAuth->id
            ]);

            $kpiItem->kpiAchievement()->create([
                "title" => 'Q' . $kpiItem->quarter . '-' . $kpiItem->year,
                "user_id" => $user->id,
                "category_id" => ImportedGermplasm::CATEGORY_ID,
                "date" => $kpiItem->date,
                "approval_status" => Approvement::STATUS_SUBMITED
            ]);

            (new CreateImportedGermplasmSubmitNotification)->execute($kpiItem);
            (new CreateImportedGermplasmSubmitTask)->execute($kpiItem, $userAuth);
        }

    }
}
