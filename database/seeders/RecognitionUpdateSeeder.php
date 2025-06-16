<?php

namespace Database\Seeders;

use App\Actions\DataMigration\Update\TransformRecognition;
use App\Helpers\EtlHelper;
use Illuminate\Database\Seeder;

class RecognitionUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-update/R&D Recognition.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;

        while (($row = fgetcsv($file)) !== false) {

            if ($hasHeader && !$isHeaderSkiped) {
                $isHeaderSkiped = true;
                continue;
            }

            $arrInsert = $this->transform($row);

            (new TransformRecognition)->execute($arrInsert);
        }

        fclose($file);

        info("FINISH SEED " . __CLASS__);
    }

    protected function transform($oldRow)
    {
        $oldRow = collect($oldRow);
        $oldRow = $oldRow->map(function ($item) {
            $item = trim($item);
            $item = EtlHelper::transformNull($item);
            return $item;
        });

        return [
            "date" => $oldRow[0], //
            "project" => $oldRow[1], //
            "project_leader" => $oldRow[2], //
            "recognitions" => $oldRow[3], //
            "type" => $oldRow[4], //
            "file" => $oldRow[5],
            "status" => $oldRow[6]
        ];
    }
}
