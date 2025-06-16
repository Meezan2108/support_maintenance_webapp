<?php

namespace App\Actions\Test\ProjectMonitoring;

use App\Models\ProposalMilestone;
use App\Models\ReportQuarterly;

class CreateTrfMarTest
{
    public function execute($proposal)
    {
        $report = ReportQuarterly::create([
            'user_id' => $proposal->user_id,
            'proposal_id' => $proposal->id,
            'report_type' => ReportQuarterly::TYPE_MAR,
            'report_type_code' => ReportQuarterly::ARR_TYPE[ReportQuarterly::TYPE_MAR],
            'year' => 2023,
            'quarter' => 3,
            'approval_status' => ReportQuarterly::STATUS_DRAFT,
            'created_by' => $proposal->user_id
        ]);

        $reportMilestone = $report->reportMilestone()->create([
            'reason_not_achieved' =>  "",
            'corrective_action' => "",
            'revised_completion_date' => null,

            'request_extension' => 0,
            'new_completion_date' => null,
            'reason_for_extension' => "",
            "comments" => ''
        ]);

        $milestones = ProposalMilestone::query()
            ->where("proposal_id", $proposal->id)
            ->when($report->year && $report->quarter, function ($query) use ($report) {
                return $query->filterByQuarter($report->year, $report->quarter);
            })
            ->get();

        foreach ($milestones as $milestone) {
            $milestone->update([
                'is_achieved' => 1,
                'completion_date' => $milestone->from
            ]);
        }

        return $report;
    }
}
