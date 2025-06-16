<?php

namespace Database\Seeders;

use App\Actions\DataMigration\Update\TransformOutput;
use App\Helpers\EtlHelper;
use Illuminate\Database\Seeder;

class OutputUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-update/R&D Output.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];

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
            "project_leader" => $oldRow[0], //
            "output" => $oldRow[1], //
            "type_of_output" => $oldRow[2], //
            "status_output" => $oldRow[3], //
            "date" => $oldRow[4], //
            "project_involved" => $oldRow[5], //
            "source_project" => $oldRow[6], //
            "status_approval" => $oldRow[7], //
        ];
    }
}
