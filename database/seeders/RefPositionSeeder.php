<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefPosition;
use Illuminate\Database\Seeder;

class RefPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefPosition::truncate();
        info("START");

        $file = fopen(storage_path("csv/crims-db-v1/refJawatan.csv"), 'r');

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

        RefPosition::insert($arrInsert);

        fclose($file);

        info("FINISH SEED refBahagian");
    }

    public function transform(array $oldRow)
    {
        $oldRow = collect($oldRow);
        $oldRow = $oldRow->map(function ($item) {
            $item = trim($item);
            return EtlHelper::transformNull($item);
        });

        return [
            "code" => $oldRow[0],
            "description" => $oldRow[1],
        ];
    }
}
