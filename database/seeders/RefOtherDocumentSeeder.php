<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefOtherDocument;
use App\Models\RefPatent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefOtherDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefOtherDocument::truncate();
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-v1/refOtherDocument.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];
        while (($row = fgetcsv($file)) !== FALSE) {
            if ($hasHeader && !$isHeaderSkiped) {
                $isHeaderSkiped = true;
                continue;
            }
            $arrInsert[] = $this->transform($row);
        }

        RefOtherDocument::insert($arrInsert);

        fclose($file);

        info("FINISH SEED " . __CLASS__);
    }

    protected function transform($oldRow)
    {
        $oldRow = collect($oldRow);
        $oldRow = $oldRow->map(function ($item) {
            $item = trim($item);
            return EtlHelper::transformNull($item);
        });

        return [
            "code" => $oldRow[0],
            "description" => $oldRow[1],
            "detail" => $oldRow[2],
            "created_at" => now(),
            "updated_at" => now()
        ];
    }
}
