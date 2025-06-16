<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefResearchCluster;
use Illuminate\Database\Seeder;

class RefResearchClusterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefResearchCluster::truncate();
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-v1/refResearchCluster.csv"), 'r');

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

        RefResearchCluster::insert($arrInsert);

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
            "created_at" => now(),
            "updated_at" => now()
        ];
    }
}
