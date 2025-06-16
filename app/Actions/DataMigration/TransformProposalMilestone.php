<?php

namespace App\Actions\DataMigration;

use App\Models\Originalable;
use App\Models\Proposal;
use App\Models\ProposalProjectTeam;
use App\Models\RefProjectCostSeries;
use App\Models\User;

class TransformProposalMilestone
{
    public function execute($arrData)
    {
        $originalDataProject = Originalable::query()
            ->where("original_id", $arrData["ProjectCode"])
            ->where("table_source", "project")
            ->first();

        $proposal = optional($originalDataProject)->referenceTable;
        if (!$proposal) {
            print_r($arrData["ProjectCode"]);
            echo "\n";
            return;
        }

        if ($arrData["Status"]) {
            print_r($arrData);
        }

        if ($arrData["Type"] == "M") {
            $this->createMilestone($proposal, $arrData);
        } else {
            $this->createActivities($proposal, $arrData);
        }
    }

    public function createMilestone(Proposal $proposal, $arrData)
    {
        $originalData = Originalable::query()
            ->where("original_id", $arrData["MileStoneCode"])
            ->where("table_source", "milestonedetail")
            ->first();

        $milestone = optional($originalData)->referenceTable;

        if (!$milestone) {
            $milestone = $proposal->milestones()->create([
                "activities" => $arrData["MilestoneName"],
                "from" => $arrData["StartDate"],
                "to" => $arrData["EndDate"],
                "is_achieved" => 1,
            ]);
        } else {
            $milestone->update([
                "activities" => $arrData["MilestoneName"],
                "from" => $arrData["StartDate"],
                "to" => $arrData["EndDate"],
                "is_achieved" => 1,
            ]);
        }

        if (!$originalData) {
            $milestone->originalable()->create([
                "table_source" => "milestonedetail",
                "original_id" => $arrData["MileStoneCode"],
                "original_data" => $arrData
            ]);
        } else {
            $originalData->update([
                "original_data" => $arrData
            ]);
        }
    }

    public function createActivities(Proposal $proposal, $arrData)
    {
        $originalData = Originalable::query()
            ->where("original_id", $arrData["MileStoneCode"])
            ->where("table_source", "milestonedetail")
            ->first();

        $activity = optional($originalData)->referenceTable;

        if (!$activity) {
            $activity = $proposal->activities()->create([
                "activities" => $arrData["MilestoneName"],
                "from" => $arrData["StartDate"],
                "to" => $arrData["EndDate"],
                "order" => 0,
            ]);
        } else {
            $activity->update([
                "activities" => $arrData["MilestoneName"],
                "from" => $arrData["StartDate"],
                "to" => $arrData["EndDate"],
                "order" => 0,
            ]);
        }

        if (!$originalData) {
            $activity->originalable([
                "table_source" => "milestonedetail",
                "original_id" => $arrData["MileStoneCode"],
                "original_data" => $arrData
            ]);
        } else {
            $originalData->update([
                "original_data" => $arrData
            ]);
        }
    }
}
