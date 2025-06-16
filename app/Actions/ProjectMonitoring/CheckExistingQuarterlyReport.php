<?php

namespace App\Actions\ProjectMonitoring;

use App\Models\Proposal;
use App\Models\ReportQuarterly;

class CheckExistingQuarterlyReport
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Proposal $proposal, int $year, int $quarter, $type = ReportQuarterly::TYPE_MAR)
    {
        $report = ReportQuarterly::query()
            ->where("proposal_id", $proposal->id)
            ->where("year", $year)
            ->where("quarter", $quarter)
            ->where("report_type", $type)
            ->whereNotIn("approval_status", [
                ReportQuarterly::STATUS_REJECTED,
                ReportQuarterly::STATUS_DRAFT
            ])
            ->first();

        return $report;
    }
}
