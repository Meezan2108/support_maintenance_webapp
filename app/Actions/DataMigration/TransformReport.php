<?php

namespace App\Actions\DataMigration;

use App\Models\Approvement;
use App\Models\Originalable;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use DateTime;

class TransformReport
{
    public function execute($arrData)
    {
        $originalDataProject = Originalable::query()
            ->where("original_id", $arrData["idproject"])
            ->where("table_source", "project")
            ->first();

        $proposal = optional($originalDataProject)->referenceTable;
        if (!$proposal) {
            print_r($arrData["idproject"]);
            echo "\n";
            return;
        }

        if ($arrData["reporttype"] == 1) { // progress report
            $report = $this->createProgressResearch($proposal, $arrData);
        } else {
            $report = $this->createEndOfProject($proposal, $arrData);
        }

        return $report;
    }

    public function createProgressResearch(Proposal $proposal, $arrData)
    {
        echo "Progress Research :";
        echo $arrData["idproject"] . " - " . $arrData["reporttype"] . " - " . $arrData["ReportCategory"] . "\n";

        $milestoneOldData = Originalable::query()
            ->where("table_source", "milestonedetail")
            ->where("original_id", $arrData["idmilestone"])
            ->first();

        if (!$milestoneOldData) {
            return false;
        }

        $milestone = $milestoneOldData->referenceTable;

        $milestone->is_achieved = $arrData["status"] ?? 0;


        // Convert the date to a DateTime object
        $dateTime = new DateTime($milestone->from);

        $year = $dateTime->format("Y");
        $month = $dateTime->format('n');
        $quarter = $this->getQuarter($month);

        $reportQuarter = $proposal->reportQuarterly()
            ->where("report_type", ReportQuarterly::TYPE_MAR)
            ->where("year", $year)
            ->where("quarter", $quarter)
            ->first();

        if (!$reportQuarter) {
            $reportQuarter = $proposal->reportQuarterly()
                ->create([
                    "user_id" => $proposal->user_id,
                    "report_type" => ReportQuarterly::TYPE_MAR,
                    "report_type_code" => "MAR",
                    "year" => $year,
                    "quarter" => $quarter,
                    "approval_status" => Approvement::STATUS_APPROVED
                ]);
        } else {
            $reportQuarter->update([
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        }

        $reportQuarter->created_at = $arrData["datesubmit"];
        $reportQuarter->updated_at = $arrData["datesubmit"];

        $reportQuarter->save();

        $reportMAR = $reportQuarter->reportMilestone;

        if (!$reportMAR) {
            $reportMAR = $reportQuarter->reportMilestone()
                ->create([
                    "comments" => $arrData["Description"]
                ]);
        } else {
            $reportMAR->update([
                "comments" => $arrData["Description"]
            ]);
        }

        $reportMAR->created_at = $arrData["datesubmit"];
        $reportMAR->updated_at = $arrData["datesubmit"];

        $reportMAR->save();

        $reportOldData = Originalable::query()
            ->where("table_source", "report")
            ->where("original_id", $arrData["idreport"])
            ->first();

        if (!$reportOldData) {
            $reportMAR->originalable()->create([
                "table_source" => "report",
                "original_id" => $arrData["idreport"],
                "original_data" => $arrData,
            ]);
        } else {
            $reportOldData->update([
                "original_data" => $arrData,
            ]);
        }

        return $reportMAR;
    }

    public function createEndOfProject(Proposal $proposal, $arrData)
    {
        echo "End of Project :";
        echo $arrData["idproject"] . " - " . $arrData["reporttype"] . " - " . $arrData["ReportCategory"] . "\n";

        $endproject = $proposal->reportEndProject()
            ->where("user_id", $proposal->user_id)
            ->where("approval_status", Approvement::STATUS_APPROVED)
            ->first();

        if (!$endproject) {
            $endproject = $proposal->reportEndProject()->create([
                "user_id" => $proposal->user_id,
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        }

        $reportOldData = Originalable::query()
            ->where("table_source", "report")
            ->where("original_id", $arrData["idreport"])
            ->first();

        if (!$reportOldData) {
            $endproject->originalable()->create([
                "table_source" => "report",
                "original_id" => $arrData["idreport"],
                "original_data" => $arrData,
            ]);
        } else {
            $reportOldData->update([
                "original_data" => $arrData,
            ]);
        }

        return $endproject;
    }

    function getQuarter($month)
    {
        // Determine the quarter based on the month
        if ($month >= 1 && $month <= 3) {
            return 1;
        } elseif ($month >= 4 && $month <= 6) {
            return 2;
        } elseif ($month >= 7 && $month <= 9) {
            return 3;
        } else {
            return 4;
        }
    }
}
