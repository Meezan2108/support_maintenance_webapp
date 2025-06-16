<?php

namespace App\Actions\ProjectMonitoring\ExtensionProject;

use App\Models\ExtensionProject;
use App\Models\Proposal;
use App\Models\ProposalMilestone;

class SyncMilestone
{
    /**
     * Execute the action
     *
     * @param  Extensionproject  $application
     * @return ExtensionProject
     */
    public function execute(ExtensionProject $application)
    {
        $proposal = $application->proposal;
        $milestoneExtension = $application->granttchart;

        foreach ($milestoneExtension as $milestone) {
            $proposalMilestone = ProposalMilestone::query()
                ->where("source_id", $milestone->id)
                ->first();

            if ($proposalMilestone) {
                $proposalMilestone->update([
                    "activities" => $milestone->description,
                    "from" => $milestone->from
                ]);
            } else {
                $proposalMilestone = $proposal->milestones()->create([
                    "activities" => $milestone->description,
                    "from" => $milestone->from,
                    "type" => ProposalMilestone::TYPE_EXTENSION,
                    "source_id" => $milestone->id
                ]);
            }
        }

        return $application;
    }
}
