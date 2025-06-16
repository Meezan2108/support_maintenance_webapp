<?php

namespace App\Actions\KpiMonitoring;

use App\Helpers\DateHelper;
use App\Models\AnalyticalServiceLab;
use App\Models\Approvement;
use App\Models\User;

class CreateAnalyticalServiceLabBulkData
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

            $kpiItem = AnalyticalServiceLab::create([
                "user_id" => $user->id,
                "date" => DateHelper::calcDateByQuarter($data['year'], $data['quarter']),
                "year" => $data["year"],
                "quarter" => $data["quarter"],
                "no_sample" => $data["no_of_sample"],
                "no_analysis" => $data["no_of_analysis"],
                "no_analysis_protocol" => $data["no_of_analysis_protocol"],
                "created_by" => $userAuth->id
            ]);


            $kpiItem->kpiAchievement()->create([
                "title" => 'Q' . $kpiItem->quarter . '-' . $kpiItem->year,
                "user_id" => $user->id,
                "category_id" => AnalyticalServiceLab::CATEGORY_ID,
                "date" => $kpiItem->date,
                "approval_status" => Approvement::STATUS_SUBMITED
            ]);

            (new CreateAnalyticalServiceLabSubmitNotification)->execute($kpiItem);
            (new CreateAnalyticalServiceLabSubmitTask)->execute($kpiItem, $userAuth);
        }

    }
}
