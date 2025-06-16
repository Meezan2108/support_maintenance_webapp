<?php

namespace App\Actions\ProjectMonitoring\Mar;

use App\Models\ProposalMilestone;
use App\Models\ReportQuarterly;

class UpdateMilestoneAchievement
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ReportQuarterly $report, array $arrData)
    {
        $reportMilestone = $report->reportMilestone;

        $revisedCompletionDate = $arrData["revised_completion_date"] ?? null;
        $newCompletionDate = $arrData["new_completion_date"] ?? null;

        if (!$reportMilestone) {
            $reportMilestone = $report->reportMilestone()->create([
                'reason_not_achieved' => $arrData["reason_not_achieved"] ?? "",
                'corrective_action' => $arrData["corrective_action"] ?? "",
                'revised_completion_date' => $revisedCompletionDate ? $revisedCompletionDate . '-01' : null,

                'request_extension' => $arrData["request_extension"] ?? 0,
                'new_completion_date' => $newCompletionDate ? $newCompletionDate . '-01' : null,
                'reason_for_extension' => $arrData["reason_for_extension"] ?? "",
                "comments" => ''
            ]);
        }

        // dd($arrData);
        $reportMilestone->update([
            'reason_not_achieved' => $arrData["reason_not_achieved"] ?? "",
            'corrective_action' => $arrData["corrective_action"] ?? "",
            'revised_completion_date' => $revisedCompletionDate ? $revisedCompletionDate . '-01' : null,

            'request_extension' => $arrData["request_extension"] ?? 0,
            'new_completion_date' => $newCompletionDate ? $newCompletionDate . '-01' : null,
            'reason_for_extension' => $arrData["reason_for_extension"] ?? "",
        ]);


        $milestones = $arrData['milestones'];

        foreach ($milestones as $milestone) {
            $recMilestone = ProposalMilestone::query()
                ->where('id', $milestone['id'])
                ->where('proposal_id', $report->proposal_id)
                ->first();

            if (!$recMilestone) {
                continue;
            }

            $recMilestone->update([
                'is_achieved' => $milestone['is_achieved'] ?? null,
                'completion_date' => $milestone['completion_date'] ? $milestone['completion_date'] . '-01' : null
            ]);
        }
    }
}
