<?php

namespace Database\Seeders;

use App\Actions\DataMigration\Update\TransformCommercialization;
use App\Actions\DataMigration\Update\TransformIPR;
use App\Helpers\EtlHelper;
use Illuminate\Database\Seeder;

class CommercializationUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-update/Commercialization.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];

        while (($row = fgetcsv($file)) !== false) {
            if ($hasHeader && !$isHeaderSkiped) {
                $isHeaderSkiped = true;
                continue;
            }

            $arrInsert = $this->transform($row);

            (new TransformCommercialization)->execute($arrInsert);
        }

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
            "date" => $oldRow[0], //
            "project_leader" => $oldRow[1], //
            "category" => $oldRow[2], //
            "name" => $oldRow[3], //
            "taker" => $oldRow[4], //
            "status" => $oldRow[5], //
        ];
    }
}
