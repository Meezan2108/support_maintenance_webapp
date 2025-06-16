<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\Proposal;
use App\Models\ProposalResearchField;
use App\Models\refFORCategory;
use App\Models\RefResearchCluster;
use App\Models\RefResearchType;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Proposal::truncate();
        ProposalResearchField::truncate();

        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-v1/proposal.csv"), 'r');
        // $fileClean = fopen(storage_path("csv/cleanup-db-v1/proposal.csv"), 'w');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];
        $count = 0;

        $isAddHeader = false;
        while (($row = fgetcsv($file)) !== FALSE) {
            if ($hasHeader && !$isHeaderSkiped) {
                $isHeaderSkiped = true;
                continue;
            }


            $arrInsert = $this->transform($row);

            // if (!$isAddHeader) {
            //     $header = array_keys($arrInsert);
            //     fputcsv($fileClean, $header);
            //     $isAddHeader = true;
            // }

            if (!$arrInsert["ProjectLeader"] && !$arrInsert["Tajuk"]) {
                continue;
            }

            // fputcsv($fileClean, array_values($arrInsert));

            $this->load($arrInsert);
        }

        // fclose($fileClean);
        fclose($file);

        info("FINISH SEED " . __CLASS__);
    }

    protected function transform($oldRow)
    {
        $oldRow = collect($oldRow);
        $oldRow = $oldRow->map(function ($item) {
            $item = trim($item);
            $item = EtlHelper::transformNull($item);
            // $item = str_replace('\n', '\r\n', $item); // excel support
            return $item;
        });

        return [
            "IDProposal" => $oldRow[0], //
            "ProjectLeader" => $oldRow[1], //
            "Tajuk" => $oldRow[2], //
            "TarikhMula" => $oldRow[3], //
            "TarikhTamat" => $oldRow[4], //
            "Anggaran" => $oldRow[5], //
            "ProjectCat" => $oldRow[6],
            // 7 => "FileDoc1"
            // 8 => "FileDoc2"
            // 9 => "FileDoc3"
            // 10 => "FileDoc4"
            // 11 => "FileDoc5"
            "Agency" => $oldRow[12],
            "ProjectStatus" => $oldRow[13], //
            "TarikhMasuk" => $oldRow[14], //
            "Tarikh" => $oldRow[15],
            "RemarksRM" => $oldRow[16],
            "UpdatedBy" => $oldRow[17],
            "SentEmail" => $oldRow[18],
            "ProposalStatus" => $oldRow[19],
            "ApproveBy" => $oldRow[20],
            "RemarksDR" => $oldRow[21],
            "ProposalStatusDR" => $oldRow[22],
            "TarikhDR" => $oldRow[23],
            "RMK" => $oldRow[24],


            "Duration" => $oldRow[26], //

            "ResearchType" => $oldRow[25], //
            "ResearchCluster" => $oldRow[27], //

            "Keywords" => $oldRow[28], //
            "SEOSector" => $oldRow[29], //
            "SEOCategory" => $oldRow[30], //
            "SEOGroup" => $oldRow[31], //

            "FORPriCategory" => $oldRow[32], //
            "FORPriGroup" => $oldRow[33], //
            "FORPriArea" => $oldRow[34], //

            "FORSecCategory" => $oldRow[35], //
            "FORSecGroup" => $oldRow[36], //
            "FORSecArea" => $oldRow[37], //

            // "FileProposal" => $oldRow[38],
            "ProjectSummary" => $oldRow[39], //
            "ProjectObjectives" => $oldRow[40],
            "ProjectJustification" => $oldRow[41],
            "ProjectBenefit" => $oldRow[42],
            "FundSource" => $oldRow[43],
            "FilepropExtension" => $oldRow[44],
            "Bahagian" => $oldRow[45],
            "UPDATETEFDir" => $oldRow[46],
            "UPDATETEFCoor" => $oldRow[47],
            "RemarksDR1" => $oldRow[48],
            "ProposalStatusDR1" => $oldRow[49],
            "TarikhDR1" => $oldRow[50],
            "DR" => $oldRow[51],
            "DR1" => $oldRow[52],
            "SEOarea" => $oldRow[53],
            "IDBahagian" => $oldRow[54],
            "tarikhedit" => $oldRow[55],
            "editoleh" => $oldRow[56],
            "id" => $oldRow[57],
            "REMARKSKP" => $oldRow[58],
            "proposalstatuskp" => $oldRow[59],
            "tarikhkp" => $oldRow[60],
            // 61 => "FileOther1"
            // 62 => "FileOther2"
            // 63 => "FileOther3"
            // 64 => "FileOther4"
            // 65 => "Fileother1Extension"
            // 66 => "Fileother2Extension"
            // 67 => "Fileother3Extension"
            // 68 => "Fileother4Extension"
            // 69 => "Fileother5Extension"
            // 70 => "FileOther5"
        ];
    }

    public function load($arrInsert)
    {
        $projectLeader = User::where('code', $arrInsert['ProjectLeader'])
            ->first();

        if (!$projectLeader) {
            echo "Skiped: " . $arrInsert['ProjectLeader'] . "\n";
            echo $arrInsert["Tajuk"] . "\n";
            return false;
        }

        $researchType = RefResearchType::where('code', $arrInsert['ResearchType'])
            ->first();

        $researchCluster = RefResearchCluster::where('code', $arrInsert['ResearchCluster'])
            ->first();

        $proposal = Proposal::create([
            "proposal_type" => 0,
            "ref_type_of_funding_id" => 0,
            "project_leader_type" => 1,
            "options" => [
                "old_id" => $arrInsert["IDProposal"]
            ],
            "user_id" => $projectLeader->id,
            "project_title" => $arrInsert["Tajuk"],

            "project_leader_name" => $projectLeader->name,
            "project_leader_nric" => $projectLeader->nric,

            "schedule_start_date" => $arrInsert["TarikhMula"],
            "schedule_completion_date" => $arrInsert["TarikhTamat"],
            "schedule_duration" => $arrInsert["Duration"],

            "total_cost" => $arrInsert["Anggaran"],
            "project_status" => $arrInsert["ProjectStatus"],

            "ref_research_type_id" => $researchType->id,
            "ref_research_cluster_id" => $researchCluster->id,

            "keywords" => $arrInsert["Keywords"],
            "ref_seo_category_id" => $arrInsert["SEOCategory"],
            "ref_seo_group_id" => $arrInsert["SEOGroup"],
            "ref_seo_area_id" => $arrInsert["SEOarea"],

            "project_summary" => $arrInsert["ProjectSummary"],

            "approval_status" => $arrInsert["ProposalStatus"],
            "project_status" => $arrInsert["ProjectStatus"],

            "created_at" => $arrInsert["TarikhMasuk"]
        ]);

        $proposal->primaryResearchField()->create([
            'type' => 1,
            'ref_for_category_id' => $arrInsert["FORPriCategory"],
            'ref_for_group_id' => $arrInsert["FORPriGroup"],
            'ref_for_area_id' => $arrInsert["FORPriArea"],
        ]);

        $proposal->secondaryResearchField()->create([
            'type' => 2,
            'ref_for_category_id' => $arrInsert["FORSecCategory"],
            'ref_for_group_id' => $arrInsert["FORSecGroup"],
            'ref_for_area_id' => $arrInsert["FORSecArea"],
        ]);
    }
}
