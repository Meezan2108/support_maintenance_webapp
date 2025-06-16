<?php

namespace Database\Seeders;

use App\Actions\DataMigration\TransformIPR;
use App\Helpers\EtlHelper;
use Illuminate\Database\Seeder;

class IprOldDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-v1/IntellectualProperty.csv"), 'r');

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

            (new TransformIPR)->execute($arrInsert);
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
            "idintellectualproperty" => $oldRow[0], //
            "idproject" => $oldRow[1], //
            "idmypo" => $oldRow[2], //
            "title" => $oldRow[3], //
            "tahun" => $oldRow[4], //
            "type" => $oldRow[5], //
            "ext" => $oldRow[6], //
            "FileReport" => $oldRow[7], //
        ];
    }
}
