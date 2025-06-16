<?php

namespace App\Actions\ManagementFund;

use App\Models\Proposal;

class CreateResearchApproach
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Proposal $proposal, array $arrData)
    {
        $arrData['schedule_start_date'] = $arrData['schedule_start_date']
            ? $arrData['schedule_start_date'] . '-01'
            : null;

        $proposal->update([
            'research_methodology' => $arrData['research_methodology'],
            'risk_factor' => $arrData['risk_factor'],
            'risk_technical' => $arrData['risk_technical'],
            'risk_budget' => $arrData['risk_budget'],
            'risk_timing' => $arrData['risk_timing'],

            'schedule_start_date' => $arrData['schedule_start_date'],
            'schedule_duration' => $arrData['schedule_duration'],
        ]);

        $proposal = $this->updateActivities($proposal, $arrData["activities"] ?? []);
        $proposal = $this->updateMilestones($proposal, $arrData["milestones"] ?? []);

        return $proposal;
    }

    protected function updateActivities(Proposal $proposal, array $activities)
    {
        $activitiesId = [];
        foreach ($activities as $key => $item) {
            $activity = null;
            if ($item["id"] ?? null) {
                $activity = $proposal->activities()
                    ->where("id", $item["id"] ?? null)
                    ->first();
            }

            $item['from'] = $item['from'] ?  $item['from'] . '-01' : null;
            $item['to'] = $item['to'] ?  $item['to'] . '-01' : null;

            $item["order"] = $key + 1;

            if ($activity) {
                $activity->update($item);
            } else {
                $activity = $proposal->activities()
                    ->create($item, $item["id"] ?? null);
            }

            $activitiesId[] = $activity->id;
        }

        $proposal->activities()
            ->whereNotIn("id", $activitiesId)
            ->delete();


        return $proposal;
    }

    protected function updateMilestones(Proposal $proposal, array $milestones)
    {
        $milestonesId = [];
        foreach ($milestones as $key => $item) {
            $milestone = null;
            if ($item["id"] ?? null) {
                $milestone = $proposal->milestones()
                    ->where("id", $item["id"] ?? null)
                    ->first();
            }

            $item['to'] = $item['from'];

            $item["order"] = $key + 1;

            if ($milestone) {
                $milestone->update($item);
            } else {
                $milestone = $proposal->milestones()
                    ->create($item);
            }

            $milestonesId[] = $milestone->id;
        }

        $proposal->milestones()
            ->whereNotIn("id", $milestonesId)
            ->delete();


        return $proposal;
    }
}
