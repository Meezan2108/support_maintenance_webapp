<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefForCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefForCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefForCategory::truncate();
        info("START SEED " . __CLASS__);
        DB::unprepared('SET IDENTITY_INSERT ref_for_category ON');

        $file = fopen(storage_path("csv/crims-db-v1/refFORCategory.csv"), 'r');

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

        RefForCategory::insert($arrInsert);

        DB::unprepared('SET IDENTITY_INSERT ref_for_category OFF');

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
            "id" => $oldRow[0],
            "description" => $oldRow[1],
            "code" => $oldRow[2],
            "detail" => $oldRow[3],
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
