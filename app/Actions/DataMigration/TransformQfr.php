<?php

namespace App\Actions\DataMigration;

use App\Models\Approvement;
use App\Models\Originalable;
use App\Models\Proposal;
use App\Models\ProposalProjectTeam;
use App\Models\RefProjectCostSeries;
use App\Models\ReportQuarterly;
use App\Models\User;
use DateTime;

class TransformQfr
{
    public function execute($arrData)
    {
        $originalDataProject = Originalable::query()
            ->where("original_id", $arrData["PROJECTNUMBER"])
            ->where("table_source", "project")
            ->first();

        $proposal = optional($originalDataProject)->referenceTable;
        if (!$proposal) {
            print_r($arrData["PROJECTNUMBER"]);
            echo "\n";
            return;
        }

        $reportQuarter = $this->createReportQuarter($proposal, $arrData);

        $this->createQfr($reportQuarter, $proposal, $arrData);
    }

    public function createReportQuarter(Proposal $proposal, $arrData)
    {
        // Convert the date to a DateTime object
        $dateTime = new DateTime($arrData["TARIKHTERIMA"]);

        $year = $dateTime->format("Y");
        $month = $dateTime->format('n');
        $quarter = $this->getQuarter($month);

        $reportQuarter = $proposal->reportQuarterly()
            ->where("report_type", ReportQuarterly::TYPE_QFR)
            ->where("year", $year)
            ->where("quarter", $quarter)
            ->first();

        if (!$reportQuarter) {
            $reportQuarter = $proposal->reportQuarterly()
                ->create([
                    "user_id" => $proposal->user_id,
                    "report_type" => ReportQuarterly::TYPE_QFR,
                    "report_type_code" => "QFR",
                    "year" => $year,
                    "quarter" => $quarter,
                    "approval_status" => Approvement::STATUS_APPROVED
                ]);
        } else {
            $reportQuarter->update([
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        }

        $reportQuarter->created_at = $arrData["TARIKHTERIMA"];
        $reportQuarter->updated_at = $arrData["TARIKHTERIMA"];

        $reportQuarter->save();

        return $reportQuarter;
    }

    public function createQfr(ReportQuarterly $reportQuarter, Proposal $proposal, array $arrData)
    {
        $qfr = $reportQuarter->reportQuarterlyFinancial;

        if (!$qfr) {
            $qfr = $reportQuarter->reportQuarterlyFinancial()
                ->create([
                    "total_recieved" => $arrData["JUMLAHTERIMA"],
                    "total_expenditure" => 0,
                    "is_inline_plan" => 1
                ]);
        } else {
            $qfr->update([
                "total_recieved" => $arrData["JUMLAHTERIMA"],
                "total_expenditure" => 0,
                "is_inline_plan" => 1
            ]);
        }

        $refProjectCostSeries = RefProjectCostSeries::all();
        $projectCosts = $proposal->projectCost;

        foreach ($refProjectCostSeries as $series) {
            $totalCost = $projectCosts->firstWhere("ref_project_cost_series_id", $series->id)->sum("cost");

            $detail = $qfr->detail()
                ->where("ref_project_cost_series_id", $series->id)
                ->first();

            if (!$detail) {
                $detail = $qfr->detail()->create([
                    "ref_project_cost_series_id" => $series->id,
                    "total_approved" => $totalCost,
                    "total_recieved" => $arrData[$series->jseries_code] ?? 0,
                    "total_expenditure" => 0
                ]);
            } else {
                $detail->update([
                    "total_approved" => $totalCost,
                    "total_recieved" => $arrData[$series->jseries_code] ?? 0,
                    "total_expenditure" => 0
                ]);
            }
        }

        $originalable = $qfr->originalable()
            ->where("table_source", "jseriesterima")
            ->where("original_id", $arrData["IDJSERIESTERIMA"])
            ->first();

        if (!$originalable) {
            $qfr->originalable()->create([
                "table_source" => "jseriesterima",
                "original_id" => $arrData["IDJSERIESTERIMA"],
                "original_data" => $arrData
            ]);
        } else {
            $originalable->update([
                "original_data" => $arrData
            ]);
        }
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
