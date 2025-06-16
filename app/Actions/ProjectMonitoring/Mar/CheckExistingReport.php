<?php

namespace App\Actions\ProjectMonitoring\Mar;

use App\Models\Proposal;
use App\Models\ReportQuarterly;

class CheckExistingReport
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Proposal $proposal, int $year, int $quarter)
    {
        $report = ReportQuarterly::query()
            ->where("proposal_id", $proposal->id)
            ->where("year", $year)
            ->where("quarter", $quarter)
            ->where("report_type", ReportQuarterly::TYPE_MAR)
            ->whereNotIn("approval_status", [
                ReportQuarterly::STATUS_REJECTED,
                ReportQuarterly::STATUS_DRAFT
            ])
            ->first();

        return $report;
    }
}
