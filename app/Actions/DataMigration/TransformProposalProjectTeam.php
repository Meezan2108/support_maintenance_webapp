<?php

namespace App\Actions\DataMigration;

use App\Models\Originalable;
use App\Models\Proposal;
use App\Models\ProposalProjectTeam;
use App\Models\User;

class TransformProposalProjectTeam
{
    public function execute($arrData)
    {
        if (!$arrData["NoKT"]) return;

        $originalDataProject = Originalable::query()
            ->where("original_id", $arrData["IDProject"])
            ->where("table_source", "project")
            ->first();

        $proposal = optional($originalDataProject)->referenceTable;
        if (!$proposal) {
            print_r($arrData);
            echo "\n";
            return;
        }

        $proposal = $this->extractMainTable($proposal, $arrData);
    }

    public function extractMainTable(?Proposal $proposal, $arrData)
    {
        $userData = User::query()
            ->where("code", $arrData["NoKT"])
            ->first();

        if (!$userData) {
            echo $arrData["NoKT"] . "\n";
            return;
        }

        $arrInsert = [
            "type" => $this->formatProjectTeamType($arrData["Position"]),
            "position" => $arrData["Position"],
            "name" => $userData->name,
            "organization" => $arrData["Org"],
            "man_month" => $arrData["ManMonth"]
        ];

        $originalData = Originalable::query()
            ->where("original_id", $arrData["PTCode"])
            ->where("table_source", "ProjectTeam")
            ->first();

        $team = optional($originalData)->referenceTable;

        if ($team) {
            $team->update($arrInsert);
        } else {
            $team = $proposal->teams()->create($arrInsert);
        }

        $arrOriginal = [
            "table_source" => "ProjectTeam",
            "original_id" => $arrData["PTCode"],
            "original_data" => $arrData
        ];

        if ($originalData) {
            $originalData->update($arrOriginal);
        } else {
            $team->originalable()->create($arrOriginal);
        }

        return $team;
    }

    public function formatProjectTeamType($strType)
    {
        if ($strType == "Project Leader")
            return ProposalProjectTeam::TYPE_LEADER;

        if ($strType == "Researcher")
            return ProposalProjectTeam::TYPE_RESEARCHER;

        return ProposalProjectTeam::TYPE_STAFF;
    }
}
