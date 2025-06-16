<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefForArea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefForAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefForArea::truncate();
        info("START SEED " . __CLASS__);
        DB::unprepared('SET IDENTITY_INSERT ref_for_area ON');

        $file = fopen(storage_path("csv/crims-db-v1/refFORArea.csv"), 'r');

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

        RefForArea::insert($arrInsert);

        fclose($file);

        DB::unprepared('SET IDENTITY_INSERT ref_for_area OFF');
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
            "code" => str_pad($oldRow[0], 5, '0', STR_PAD_LEFT),
            "description" => $oldRow[1],
            "ref_for_group_id" => $oldRow[2],
            "detail" => $oldRow[3],
            "created_at" => now(),
            "updated_at" => now()
        ];
    }
}
