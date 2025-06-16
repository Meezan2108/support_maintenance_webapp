<?php

namespace App\Actions\ManagementFund;

use App\Models\Proposal;

class CreateProjectCollaboration
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Proposal $proposal, array $arrData)
    {
        // collaborations

        $organizations = $arrData["organizations"] ?? [];
        $organizations = collect($organizations)->map(function ($item) {
            $item["type"] = 1;
            return $item;
        })->toArray();

        $industries = $arrData["industries"] ?? [];
        $industries = collect($industries)->map(function ($item) {
            $item["type"] = 2;
            $item["other"] = "";
            return $item;
        })->toArray();

        $collaborations = array_merge($organizations, $industries);
        $proposal = $this->syncOrganization($proposal, $collaborations);

        // project teams

        $projectLeader = $arrData["project_leaders"] ?? [];
        $projectLeader = collect($projectLeader)->map(function ($item) {
            $item["type"] = 1;
            $item["position"] = "";
            return $item;
        })->toArray();

        $researchers = $arrData["researchers"] ?? [];
        $researchers = collect($researchers)->map(function ($item) {
            $item["type"] = 2;
            $item["position"] = "";
            return $item;
        })->toArray();

        $staffs = $arrData["staffs"] ?? [];
        $staffs = collect($staffs)->map(function ($item) {
            $item["type"] = 3;
            $item["position"] = "";
            return $item;
        })->toArray();

        $teams = array_merge($projectLeader, $researchers, $staffs);
        $proposal = $this->syncTeam($proposal, $teams);

        return $proposal;
    }

    protected function syncOrganization(Proposal $proposal, array $collaborations)
    {
        $organizationsId = [];
        foreach ($collaborations as $key => $item) {
            $organization = null;
            if ($item["id"] ?? null) {
                $organization = $proposal->collaborations()
                    ->where("id", $item["id"])
                    ->first();
            }

            // $item["order"] = $key + 1;

            if ($organization) {
                $organization->update($item);
            } else {
                $organization = $proposal->collaborations()
                    ->create($item);
            }

            $organizationsId[] = $organization->id;
        }

        $proposal->collaborations()
            ->whereNotIn("id", $organizationsId)
            ->delete();

        return $proposal;
    }

    protected function syncTeam(Proposal $proposal, array $teams)
    {
        $teamsId = [];
        foreach ($teams as $key => $item) {
            $team = null;
            if ($item["id"] ?? null) {
                $team = $proposal->teams()
                    ->where("id", $item["id"])
                    ->first();
            }

            // $item["order"] = $key + 1;

            if ($team) {
                $team->update($item);
            } else {
                $team = $proposal->teams()
                    ->create($item);
            }

            $teamsId[] = $team->id;
        }

        $proposal->teams()
            ->whereNotIn("id", $teamsId)
            ->delete();

        return $proposal;
    }
}
