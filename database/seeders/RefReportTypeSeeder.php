<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefPubType;
use App\Models\RefReportType;
use Illuminate\Database\Seeder;

class RefReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefReportType::truncate();
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-v1/refReportOthers.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = collect([]);
        while (($row = fgetcsv($file)) !== FALSE) {
            if ($hasHeader && !$isHeaderSkiped) {
                $isHeaderSkiped = true;
                continue;
            }

            $temp = $this->transform($row);
            if (!$temp) {
                continue;
            }

            $arrInsert->push($temp);
        }

        RefReportType::insert($arrInsert->toArray());

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
