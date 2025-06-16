<?php

namespace Database\Seeders;

use App\Actions\DataMigration\StoreDocumentProposal;
use App\Actions\DataMigration\TransformProposal;
use App\Helpers\EtlHelper;
use Illuminate\Database\Seeder;

class ProposalOldDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-v1/Proposal.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];
        $count = 0;

        $isAddHeader = false;

        while (($row = fgetcsv($file)) !== FALSE) {
            if ($hasHeader && !$isHeaderSkiped) {
                // dd($row);
                $isHeaderSkiped = true;
                continue;
            }


            $arrInsert = $this->transform($row);

            $proposal = (new TransformProposal)->execute($arrInsert);


            if (!$proposal) continue;

            $proposal->fileable()->forceDelete();

            if ($arrInsert["FilepropExtension"] && $arrInsert["FileProposal"]) {
                $ext = EtlHelper::mimeToExtension($arrInsert["FilepropExtension"]);
                (new StoreDocumentProposal)->execute($proposal, [
                    "file_name" => "FileProposal" . $ext,
                    "file_type" => $arrInsert["FilepropExtension"],
                    "file_size" => 0,
                    "file" => $arrInsert["FileProposal"]
                ]);
            }

            for ($i = 1; $i <= 5; $i++) {
                if (!$arrInsert["Fileother{$i}Extension"]) {
                    continue;
                }

                $ext = EtlHelper::mimeToExtension($arrInsert["Fileother{$i}Extension"]);
                (new StoreDocumentProposal)->execute($proposal, [
                    "file_name" => "FileOther{$i}" . $ext,
                    "file_type" => $arrInsert["Fileother{$i}Extension"],
                    "file_size" => 0,
                    "file" => $arrInsert["FileOther{$i}"]
                ]);
            }
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

            "FileDoc1" => $oldRow[7],
            "FileDoc2" => $oldRow[8],
            "FileDoc3" => $oldRow[9],
            "FileDoc4" => $oldRow[10],
            "FileDoc5" => $oldRow[11],

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

            "FileProposal" => $oldRow[38],
            "FilepropExtension" => $oldRow[44],

            "ProjectSummary" => $oldRow[39], //
            "ProjectObjectives" => $oldRow[40],
            "ProjectJustification" => $oldRow[41],
            "ProjectBenefit" => $oldRow[42],
            "FundSource" => $oldRow[43],

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

            "FileOther1" => $oldRow[61],
            "FileOther2" => $oldRow[62],
            "FileOther3" => $oldRow[63],
            "FileOther4" => $oldRow[64],
            "FileOther5" => $oldRow[70],

            "Fileother1Extension" => $oldRow[65],
            "Fileother2Extension" => $oldRow[66],
            "Fileother3Extension" => $oldRow[67],
            "Fileother4Extension" => $oldRow[68],
            "Fileother5Extension" => $oldRow[69],
        ];
    }
}
