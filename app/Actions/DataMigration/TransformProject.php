<?php

namespace App\Actions\DataMigration;

use App\Models\Approvement;
use App\Models\Originalable;
use App\Models\Proposal;
use App\Models\RefDivision;
use App\Models\RefTypeOfFunding;
use App\Models\User;

class TransformProject
{
    public function execute($arrData)
    {
        if (!$arrData["Tajuk"]) return;

        $originalData = Originalable::query()
            ->where("original_id", $arrData["IDProposal"])
            ->where("table_source", "proposal")
            ->first();

        $proposal = $this->extractMainTable($originalData, $arrData);

        $this->extractRef($proposal, $arrData);
    }

    public function extractMainTable($originalData, $arrData)
    {
        $projectLeader = User::where('code', $arrData["ProjectLeader"])
            ->first();

        $rmc = User::where("original_id", $arrData["RMK"])
            ->first();

        $typeOfFund = RefTypeOfFunding::where("id", $arrData["ProjectCat"])
            ->first();

        $arrInsert = [
            "application_id" => $arrData["IDProposal"],
            "user_id" => optional($projectLeader)->id ?? 0,
            "project_number" => $arrData["ProjectNumber"],
            "project_title" => $arrData["Tajuk"],

            "proposal_type" => $typeOfFund->type,
            "ref_type_of_funding_id" => $typeOfFund->id,
            "project_leader_type" => 1,
            // IDBahagian => Ref

            "schedule_start_date" => $arrData["TarikhMula"],
            "schedule_completion_date" => $arrData["TarikhTamat"],
            "schedule_duration" => $arrData["Duration"],

            // StatusProject => Ref

            "approved_cost" => $arrData["Fund"],

            // RMK => Ref
            "rmc_id" => optional($rmc)->id,

            "ref_research_type_id" => $arrData["ResearchType"],
            "ref_research_cluster_id" => $arrData["ResearchCluster"],

            // SEOSector

            "ref_seo_category_id" => $arrData["SEOCategory"],
            "ref_seo_group_id" => $arrData["SEOGroup"],
            "ref_seo_area_id" => $arrData["SEOArea"],

            // for primary 

            "keywords" => explode(",", $arrData["Keywords"]),
            "reference" => $arrData["ProjectReference"],

            "approval_at" => $arrData["ApprovedDate"],
            "approval_status" => Approvement::STATUS_APPROVED,
            'project_status' => $this->convertStatusProject($arrData["StatusProject"]),

        ];

        $arrOriginal = [
            "table_source" => "project",
            "original_id" => $arrData["IDProject"],
            "original_data" => $arrData
        ];


        $originalDataProject = Originalable::query()
            ->where("table_source", "project")
            ->where("original_id", $arrData["IDProject"])
            ->first();

        $proposal = optional($originalData)->referenceTable
            ?? optional($originalDataProject)->referenceTable;

        if ($proposal) {
            $proposal->update($arrInsert);
        } else {
            $proposal = Proposal::create($arrInsert);
        }

        if ($originalDataProject) {
            $originalDataProject->update($arrOriginal);
        } else {
            $proposal->originalable()->create($arrOriginal);
        }

        $proposal->created_at = $arrData["TarikhMula"];
        $proposal->updated_at = $arrData["TarikhTamat"] ?? $arrData["TarikhMula"];

        $proposal->save();


        return $proposal;
    }

    public function extractRef(Proposal $proposal, $arrData)
    {
        $division = RefDivision::where("code", $arrData["IDBahagian"])
            ->first();

        $researcherData = User::where("code", $arrData["ProjectLeader"])
            ->first();

        $researcher = $proposal->researcher;
        $arrResearcher = [
            "name" => optional($researcherData)->name ?? '',
            "nric" => optional($researcherData)->nirc ?? '',
            "ref_division_id" => optional($division)->id,
            "ref_position_id" => optional($researcherData)->ref_position_id,
            "tel_no" => optional($researcherData)->tel_no,
            "fax_no" => optional($researcherData)->fax_no,
            "email" => optional($researcherData)->email
        ];

        if ($researcher) {
            $researcher->update($arrResearcher);
        } else {
            $proposal->researcher()->create($arrResearcher);
        }

        if ($arrData["FORPriCategory"] || $arrData["FORPriGroup"] || $arrData["FORPriArea"]) {
            $primary = $proposal->primaryResearchField;
            $arrPrimary = [
                "type" => 1,
                "ref_for_category_id" => $arrData["FORPriCategory"],
                "ref_for_group_id" => $arrData["FORPriGroup"],
                "ref_for_area_id" => $arrData["FORPriArea"]
            ];

            if ($primary) {
                $primary->update($arrPrimary);
            } else {
                $proposal->primaryResearchField()->create($arrPrimary);
            }
        }

        if ($arrData["FORSecCategory"] || $arrData["FORSecGroup"] || $arrData["FORSecArea"]) {
            $secondary = $proposal->secondaryResearchField;
            $arrSecondary = [
                "type" => 2,
                "ref_for_category_id" => $arrData["FORSecCategory"],
                "ref_for_group_id" => $arrData["FORSecGroup"],
                "ref_for_area_id" => $arrData["FORSecArea"]
            ];

            if ($secondary) {
                $secondary->update($arrSecondary);
            } else {
                $proposal->secondaryResearchField()->create($arrSecondary);
            }
        }

        if ($arrData["Objective"]) {

            $objectives = $proposal->objectives()
                ->where("description", $arrData["Objective"])
                ->first();

            if ($objectives) {
                $objectives->update([
                    "description" => $arrData["Objective"]
                ]);
            } else {
                $proposal->objectives()->create([
                    "description" => $arrData["Objective"]
                ]);
            }
        }
    }

    public function convertStatusProject($status)
    {
        $oldStatus = [
            0 => [
                "convert_id" => 1,
                "description" => "Proposal"
            ],
            1 => [
                "convert_id" => 2,
                "description" => "On-Going"
            ],
            2 => [
                "convert_id" => 5,
                "description" => "Terminated"
            ],
            3 => [
                "convert_id" => -1,
                "description" => "Abandoned"
            ],
            4 => [
                "convert_id" => -2,
                "description" => "Suspended"
            ],
            5 => [
                "convert_id" => 3,
                "description" => "Completed"
            ]
        ];

        return $oldStatus[$status]["convert_id"] ?? 2;
    }
}
