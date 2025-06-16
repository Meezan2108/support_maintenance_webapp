<?php

namespace App\Actions\Test\ProjectMonitoring;

use App\Models\RefProjectCostSeries;
use App\Models\ReportQuarterly;

class CreateTrfQfrTest
{
    public function execute($proposal)
    {
        $report = ReportQuarterly::create([
            'user_id' => $proposal->user_id,
            'proposal_id' => $proposal->id,
            'report_type' => ReportQuarterly::TYPE_QFR,
            'report_type_code' => ReportQuarterly::ARR_TYPE[ReportQuarterly::TYPE_QFR],
            'year' => 2023,
            'quarter' => 3,
            'approval_status' => ReportQuarterly::STATUS_DRAFT,
            'created_by' => $proposal->user_id
        ]);

        $reportFinancial = $report->reportQuarterlyFinancial()->create([
            "total_recieved" => $arrData["total_recieved"] ?? 0,
            "total_expenditure" => $arrData["total_expenditure"] ?? 0,
            "is_inline_plan" => $arrData["is_inline_plan"] ?? null,
        ]);

        $projectCostSeries = RefProjectCostSeries::query()
            ->whereIn("vseries_code", ["V21000", "V26000", "V28000", "V29000"])
            ->get();

        $totalExpenditure = 0;
        $totalReceived = 0;
        foreach ($projectCostSeries as $projecCost) {
            $detail = $reportFinancial->detail()->create([
                "ref_project_cost_series_id" => $projecCost->id,
                "total_approved" => 100,
                "total_recieved" => 100,
                "total_expenditure" => 100
            ]);

            $totalExpenditure += $detail->total_expenditure;
            $totalReceived += $detail->total_recieved;
        }

        $reportFinancial->total_recieved = $totalReceived;
        $reportFinancial->total_expenditure = $totalExpenditure;
        $reportFinancial->save();

        return $report;
    }
}
