<?php

namespace App\Actions\ProjectMonitoring\Qfr;

use App\Models\ProposalMilestone;
use App\Models\ReportQuarterly;

class UpdateFinancialProgress
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ReportQuarterly $report, array $arrData)
    {
        $reportFinancial = $report->reportQuarterlyFinancial;
        if (!$reportFinancial) {
            $reportFinancial = $report->reportQuarterlyFinancial()->create([
                "total_recieved" => $arrData["total_recieved"] ?? 0,
                "total_expenditure" => $arrData["total_expenditure"] ?? 0,
                "is_inline_plan" => $arrData["is_inline_plan"] ?? null
            ]);
        } else {
            $reportFinancial->update([
                "total_recieved" => $arrData["total_recieved"] ?? 0,
                "total_expenditure" => $arrData["total_expenditure"] ?? 0,
                "is_inline_plan" => $arrData["is_inline_plan"] ?? null
            ]);
        }


        $projectExpenditure = $arrData['actual_project_expenditure'];
        foreach ($projectExpenditure as $data) {
            $detail = $reportFinancial->detail()
                ->where("ref_project_cost_series_id", $data["ref_project_cost_series_id"] ?? false)
                ->first();

            if (!$detail) {
                $reportFinancial->detail()->create([
                    "ref_project_cost_series_id" => $data["ref_project_cost_series_id"] ?? 0,
                    "total_approved" => $data["total_approved"] ?? 0,
                    "total_recieved" => $data["total_recieved"] ?? 0,
                    "total_expenditure" => $data["total_expenditure"] ?? 0
                ]);
            } else {
                $detail->update([
                    "total_approved" => $data["total_approved"] ?? 0,
                    "total_recieved" => $data["total_recieved"] ?? 0,
                    "total_expenditure" => $data["total_expenditure"] ?? 0
                ]);
            }
        }
    }
}
