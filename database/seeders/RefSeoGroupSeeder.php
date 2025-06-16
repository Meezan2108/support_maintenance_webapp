<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefSeoGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefSeoGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefSeoGroup::truncate();
        info("START SEED " . __CLASS__);

        DB::unprepared('SET IDENTITY_INSERT ' . (new RefSeoGroup())->getTable() . ' ON');

        $file = fopen(storage_path("csv/crims-db-v1/refSEOGroup.csv"), 'r');

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

        refSEOGroup::insert($arrInsert);

        fclose($file);

        DB::unprepared('SET IDENTITY_INSERT ' . (new RefSeoGroup())->getTable() . ' OFF');

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
            "code" => $oldRow[2],
            "description" => $oldRow[1],
            "ref_seo_category_id" => $oldRow[3],
            "detail" => $oldRow[4],
            "created_at" => now(),
            "updated_at" => now()
        ];
    }
}
