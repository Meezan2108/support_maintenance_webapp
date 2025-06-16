<?php

namespace App\Actions\ProjectMonitoring\Mar;

use App\Models\ProposalMilestone;
use App\Models\ReportQuarterly;

class ResetMilestoneAchievement
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ReportQuarterly $report)
    {
        if ($report->approval_status != ReportQuarterly::STATUS_DRAFT) {
            return $report;
        }

        $milestones = ProposalMilestone::query()
            ->where("proposal_id", $report->proposal_id)
            ->filterByQuarter($report->year, $report->quarter)
            ->get();

        foreach ($milestones as $milestone) {
            $recMilestone = ProposalMilestone::query()
                ->where('id', $milestone['id'])
                ->where('proposal_id', $report->proposal_id)
                ->first();

            if (!$recMilestone) continue;

            $recMilestone->update([
                'is_achieved' => null,
                'completion_date' => null
            ]);
        }
    }
}
