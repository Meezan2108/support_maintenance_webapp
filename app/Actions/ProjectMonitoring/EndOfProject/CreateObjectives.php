<?php

namespace App\Actions\ProjectMonitoring\EndOfProject;

use App\Models\Proposal;
use App\Models\ReportEndProject;

class CreateObjectives
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return ReportEndProject
     */
    public function execute(ReportEndProject $report, array $arrData, string $status)
    {
        $objectivesId = [];
        foreach ($arrData as $key => $item) {
            $objective = null;
            if ($item["id"] ?? false) {
                $objective = $report->objective()
                    ->where("id", $item["id"])
                    ->first();
            }

            $item["order"] = $key + 1;
            $item['status'] = $status;

            if ($objective) {
                $objective->update($item);
                $objectivesId[] = $objective->id;
            } else {
                $objective = $report->objective()
                    ->create($item);
                $objectivesId[] = $objective->id;
            }
        }

        $report->objective()
            ->whereNotIn('id', $objectivesId)
            ->where("status", $status)
            ->delete();

        return $report;
    }
}
