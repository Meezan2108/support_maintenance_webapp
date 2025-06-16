<?php

namespace Database\Seeders;

use App\Actions\DataMigration\TransformOutput;
use App\Helpers\EtlHelper;
use Illuminate\Database\Seeder;

class OutputOldDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-v1/Output.csv"), 'r');

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

            (new TransformOutput)->execute($arrInsert);
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
            "OutputID" => $oldRow[0], //
            "idproject" => $oldRow[1], //
            "TahunOutput" => $oldRow[2], //
            "NamaOutput" => $oldRow[3], //
            "ext" => $oldRow[4], //
            "ReportFile" => $oldRow[5], //
        ];
    }
}
