<?php

namespace App\Actions\ProjectMonitoring\ResearchProgressNoFund;

use App\Models\ReportResearchProgress;

class CreateReportObjectives
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ReportResearchProgress $report, array $arrObjectives)
    {
        foreach ($arrObjectives as $key => $objective) {
            $selObjective = $report->objective->find($objective["id"]);

            if (!$selObjective) {
                $report->objective()->create([
                    "description" => $objective["description"],
                    "order" => $key + 1
                ]);
            } else {
                $selObjective->update([
                    "description" => $objective["description"],
                    "order" => $key + 1
                ]);
            }
        }

        return $report;
    }
}
