<?php

namespace Database\Seeders;

use App\Actions\DataMigration\TransformProject;
use App\Actions\DataMigration\TransformPublication;
use App\Helpers\EtlHelper;
use Illuminate\Database\Seeder;

class PublicationOldDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-v1/Publication.csv"), 'r');

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

            (new TransformPublication)->execute($arrInsert);
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
            "idpublication" => $oldRow[0], //
            "title" => $oldRow[1], //
            "descr" => $oldRow[2], //
            "author" => $oldRow[3], //
            "type" => $oldRow[4], //
            "category" => $oldRow[5], //
            "idproject" => $oldRow[6], //
            "tahun" => $oldRow[7], //
            "url" => $oldRow[8], //
            "Bibliography" => $oldRow[9], //
            "Pubdate" => $oldRow[10], //
        ];
    }
}
