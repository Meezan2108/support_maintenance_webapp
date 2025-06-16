<?php

namespace App\Actions\DataMigration;

use App\Models\Originalable;
use App\Models\Proposal;
use App\Models\RefDivision;
use App\Models\RefTypeOfFunding;
use App\Models\User;

class TransformProposal
{
    public $dataSource = "proposal";

    public function execute($arrData)
    {
        if (!$arrData["Tajuk"]) return;

        $originalData = Originalable::query()
            ->where("original_id", $arrData["IDProposal"])
            ->where("table_source", "proposal")
            ->first();

        $proposal = optional($originalData)->referenceTable ?? null;

        $proposal = $this->extractMainTable($proposal, $arrData);

        $this->extractRef($proposal, $arrData);

        return $proposal;
    }

    public function extractMainTable($originalData, $arrData)
    {
        $projectLeader = User::where('code', $arrData["ProjectLeader"])
            ->first();

        $rmc = User::where("original_id", intval($arrData["RMK"]))
            ->first();

        $typeOfFund = RefTypeOfFunding::where("id", $arrData["ProjectCat"])
            ->first();

        $arrInsert = [
            "application_id" => $arrData["IDProposal"],
            "user_id" => optional($projectLeader)->id ?? 0,
            "project_title" => $arrData["Tajuk"],
            "proposal_type" => $typeOfFund->type,
            "ref_type_of_funding_id" => $typeOfFund->id,
            "project_leader_type" => 1,
            // "

            // IDBahagian => Ref

            "schedule_start_date" => $arrData["TarikhMula"],
            "schedule_completion_date" => $arrData["TarikhTamat"],
            "schedule_duration" => $arrData["Duration"],

            // StatusProject => Ref

            "total_cost" => $arrData["Anggaran"],


            // RMK => Ref
            "rmc_id" => optional($rmc)->id,
            "ref_research_type_id" => $arrData["ResearchType"],
            "ref_research_cluster_id" => $arrData["ResearchCluster"],

            // SEOSector

            "ref_seo_category_id" => $arrData["SEOCategory"],
            "ref_seo_group_id" => $arrData["SEOGroup"],
            "ref_seo_area_id" => $arrData["SEOarea"],

            // for primary 

            "keywords" => explode(",", $arrData["Keywords"]),
            "project_summary" => $arrData["ProjectSummary"],

            // "reference" => $arrData["ProjectReference"],

            "approval_status" => $this->convertStatusProposal($arrData["ProposalStatus"]),



            // "approval_at" => $arrData["ApproveDate"],
            // 'project_status' => $arrData["StatusProject"]
        ];

        $arrOriginal = [
            "table_source" => "proposal",
            "original_id" => $arrData["IDProposal"],
            "original_data" => $arrData
        ];

        $proposal = $originalData;

        if ($proposal) {
            $proposal->update($arrInsert);
        } else {
            $proposal = Proposal::create($arrInsert);
        }

        $proposal->created_at = $arrData["TarikhMasuk"];
        $proposal->updated_at = $arrData["tarikhedit"] ?? $arrData["TarikhMasuk"];

        $proposal->save();

        $originalData = Originalable::query()
            ->where("table_source", "proposal")
            ->where("original_id", $arrData["IDProposal"])
            ->first();

        if ($originalData) {
            $originalData->update($arrOriginal);
        } else {
            $proposal->originalable()->create($arrOriginal);
        }



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

        if ($arrData["ProjectObjectives"]) {

            $objectives = $proposal->objectives()
                ->where("description", $arrData["ProjectObjectives"])
                ->first();

            if ($objectives) {
                $objectives->update([
                    "description" => $arrData["ProjectObjectives"]
                ]);
            } else {
                $proposal->objectives()->create([
                    "description" => $arrData["ProjectObjectives"]
                ]);
            }
        }

        if ($arrData["ProjectBenefit"]) {
            $benefit = $proposal->benefits()
                ->where("description", $arrData["ProjectBenefit"])
                ->first();

            if ($benefit) {
                $benefit->update([
                    "description" => $arrData["ProjectBenefit"]
                ]);
            } else {
                $proposal->benefits()->create([
                    "description" => $arrData["ProjectBenefit"]
                ]);
            }
        }
    }

    public function convertStatusProposal($status)
    {
        $oldStatus = [
            0 => [
                "convert_id" => 1,
                "description" => "In-Process"
            ],
            1 => [
                "convert_id" => 4,
                "description" => "Approved"
            ],
            2 => [
                "convert_id" => 5,
                "description" => "Rejected"
            ],
            3 => [
                "convert_id" => 2,
                "description" => "KIV"
            ],
            4 => [
                "convert_id" => 3,
                "description" => "Resubmit"
            ]
        ];

        return $oldStatus[$status]["convert_id"] ?? 1;
    }
}
