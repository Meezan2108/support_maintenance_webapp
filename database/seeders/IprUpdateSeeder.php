<?php

namespace Database\Seeders;

use App\Actions\DataMigration\Update\TransformIPR;
use App\Helpers\EtlHelper;
use Illuminate\Database\Seeder;

class IprUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-update/Intellectual Property Right.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];

        while (($row = fgetcsv($file)) !== false) {

            if ($hasHeader && !$isHeaderSkiped) {
                $isHeaderSkiped = true;
                continue;
            }

            $arrInsert = $this->transform($row);

            (new TransformIPR)->execute($arrInsert);
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
            "output" => $oldRow[2], //
            "type" => $oldRow[3], //
            "status" => $oldRow[4], //
        ];
    }
}
