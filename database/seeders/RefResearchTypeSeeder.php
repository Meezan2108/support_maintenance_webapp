<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefResearchType;
use Illuminate\Database\Seeder;

class RefResearchTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefResearchType::truncate();

        $file = fopen(storage_path("csv/crims-db-v1/refResearchType.csv"), 'r');

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

        RefResearchType::insert($arrInsert);

        fclose($file);
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
