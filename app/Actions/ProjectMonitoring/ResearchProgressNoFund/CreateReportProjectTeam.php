<?php

namespace App\Actions\ProjectMonitoring\ResearchProgressNoFund;

use App\Models\ReportResearchProgress;

class CreateReportProjectTeam
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ReportResearchProgress $report, array $arrProjectTeam)
    {

        foreach ($arrProjectTeam as $key => $projecTeam) {
            $selItem = $report->projectTeam->find($projecTeam["id"]);

            $arrData = [
                "name" => $projecTeam["name"],
                "type" => $projecTeam["type"],
                "organization" => $projecTeam["organization"],
                "man_month" => $projecTeam["man_month"]
            ];

            if (!$selItem) {
                $report->projectTeam()->create($arrData);
            } else {
                $selItem->update($arrData);
            }
        }

        return $report;
    }
}
