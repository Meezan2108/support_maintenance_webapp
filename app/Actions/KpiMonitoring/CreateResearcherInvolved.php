<?php

namespace App\Actions\KpiMonitoring;

use App\Models\Contracts\KpiAchievementDetail;
use App\Models\KpiAchievement;

class CreateResearcherInvolved
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(KpiAchievementDetail $detailModel, KpiAchievement $kpiAchievement, array $researchers)
    {
        $arrIds = [];
        foreach ($researchers as $key => $researcher) {
            $researcherInvolved = $detailModel->researcherInvolved()
                ->where("user_id", $researcher["user_id"])
                ->where("kpi_achievement_id", $kpiAchievement->id);

            if (!$researcherInvolved) {
                $researcherInvolved->update([
                    "name" => $researcher["name"],
                ]);
            } else {
                $researcherInvolved = $detailModel->researcherInvolved()->create([
                    "user_id" => $researcher["user_id"],
                    "kpi_achievement_id" => $kpiAchievement->id,
                    "name" => $researcher["name"],
                ]);
            }

            $arrIds[] = $researcherInvolved->id;
        }

        $detailModel->researcherInvolved()
            ->whereNotIn("id", $arrIds)
            ->delete();
    }
}
