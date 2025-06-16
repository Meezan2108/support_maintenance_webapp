<?php

namespace Database\Seeders;

use App\Actions\DataMigration\TransformProject;
use App\Actions\DataMigration\TransformProposal;
use App\Helpers\EtlHelper;
use Illuminate\Database\Seeder;

class ProjectOldDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-v1/Project.csv"), 'r');

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

            (new TransformProject)->execute($arrInsert);
        }

        // fclose($fileClean);
        fclose($file);

        info("FINISH SEED " . __CLASS__);
    }

    protected function transform($oldRow)
    {
        $oldRow = collect($oldRow);
        $oldRow = $oldRow->map(function ($item) {
            $item = EtlHelper::transformNull($item);
            return $item;
        });

        return [
            "IDProject" => $oldRow[0], //
            "IDProposal" => $oldRow[1], //
            "IDBahagian" => $oldRow[2], //
            "ProjectLeader" => $oldRow[3], //
            "ProjectCat" => $oldRow[4], //
            "VotNumber" => $oldRow[5], //
            "ProjectNumber" => $oldRow[6],
            "Tajuk" => $oldRow[7],
            "Objektif" => $oldRow[8],
            "TarikhMula" => $oldRow[9],
            "TarikhTamat" => $oldRow[10],
            "Duration" => $oldRow[11],

            "StatusProject" => $oldRow[12],
            "dateEndExtend" => $oldRow[13], //
            "Fund" => $oldRow[14], //
            "Fund2" => $oldRow[15],
            // "FileMP" => $oldRow[16],
            // "FileProj" => $oldRow[17],
            // "FileDoc1" => $oldRow[18],
            // "FileDoc2" => $oldRow[19],
            // "FileDoc3" => $oldRow[20],
            // "FileDoc4" => $oldRow[21],
            // "FileDoc5" => $oldRow[22],
            // "FileReport" => $oldRow[23],
            "RMK" => $oldRow[24],

            "ResearchType" => $oldRow[25], //
            "ResearchCluster" => $oldRow[26], //

            "Keywords" => $oldRow[27], //
            "SEOSector" => $oldRow[28], //
            "SEOCategory" => $oldRow[29], //
            "SEOGroup" => $oldRow[30], //

            "FORPriArea" => $oldRow[31], //
            "FORPriCategory" => $oldRow[32], //
            "FORPriGroup" => $oldRow[33], //

            "FORSecArea" => $oldRow[34], //
            "FORSecCategory" => $oldRow[35], //
            "FORSecGroup" => $oldRow[36], //

            "Agensi" => $oldRow[37],
            "ProjectReference" => $oldRow[38],
            "ProjectRequested" => $oldRow[39],
            "FundSources" => $oldRow[40], //
            "ProjectJustification" => $oldRow[41],
            "ProgressReport" => $oldRow[42],
            "ProjectBenefit" => $oldRow[43],
            "remarkMOSTI" => $oldRow[44],
            "ExpectedStartDate" => $oldRow[45],
            "SEOArea" => $oldRow[46],
            "ApprovedDate" => $oldRow[47],
            "Objective" => $oldRow[48],
            "StatusFinancial" => $oldRow[49],
        ];
    }

    public function load($arrInsert)
    {
        return (new  TransformProposal)->execute($arrInsert);
    }
}
