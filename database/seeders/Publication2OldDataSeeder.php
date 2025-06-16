<?php

namespace Database\Seeders;

use App\Actions\DataMigration\TransformPublication2;
use App\Helpers\EtlHelper;
use Illuminate\Database\Seeder;

class Publication2OldDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-v1/Publication2.csv"), 'r');

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

            (new TransformPublication2)->execute($arrInsert);
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
            "IDPublication" => $oldRow[0], //
            "IDProject" => $oldRow[1], //
            "title" => $oldRow[2], //
            "descr" => $oldRow[3], //
            "author" => $oldRow[4], //
            "type" => $oldRow[5], //
            "tahun" => $oldRow[6], //
            "ext" => $oldRow[7], //
            "filereport" => $oldRow[8], //
        ];
    }
}
