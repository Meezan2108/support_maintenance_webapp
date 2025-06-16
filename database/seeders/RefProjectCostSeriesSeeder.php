<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefProjectCostSeries;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefProjectCostSeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefProjectCostSeries::truncate();
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-v1/AcctCodeConverter.csv"), 'r');

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

        RefProjectCostSeries::insert($arrInsert);

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
            "act_code" => $oldRow[0],
            "jseries_code" => $oldRow[1],
            "vseries_code" => $oldRow[2],
            "order" => $oldRow[3],
            "description" => $oldRow[4],
            "created_at" => now(),
            "updated_at" => now()
        ];
    }
}
