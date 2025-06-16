<?php

namespace App\Actions\ProjectMonitoring\ExtensionProject;

use App\Models\ExtensionProject;

class UpdateMilestone
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ExtensionProject $application, array $arrData)
    {
        $milestoneExtension = $arrData['milestones_extension'] ?? [];

        $arrId = [];
        foreach ($milestoneExtension as $milestone) {
            $refMilestone = $application->granttchart()
                ->where('category', ExtensionProject::CHART_MILESTONE)
                ->where('id', $milestone['id'] ?? false)
                ->first();

            $arrGranttChart = [
                'category' => ExtensionProject::CHART_MILESTONE,
                'description' => $milestone['activities'],
                'from' => $milestone['from'],
                'to' => $milestone['from']
            ];

            if ($refMilestone) {
                $refMilestone->update($arrGranttChart);
                $arrId[] = $milestone['id'];
            } else {
                $grantchart = $application->granttchart()->create($arrGranttChart);
                $arrId[] = $grantchart->id;
            }
        }

        $deletedMilestone = $application->granttchart()
            ->where('category', ExtensionProject::CHART_MILESTONE)
            ->whereNotIn('id', $arrId)
            ->get();

        foreach ($deletedMilestone as $milestone) {
            $milestone->delete();
        }

        return $application;
    }
}
