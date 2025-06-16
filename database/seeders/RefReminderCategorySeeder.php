<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefReminderCategory;
use Illuminate\Database\Seeder;

class RefReminderCategorySeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefReminderCategory::truncate();
        info("START");

        $file = fopen(storage_path("csv/crims-db-v1/refJenisReport.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];
        while (($row = fgetcsv($file)) !== false) {
            if ($hasHeader && !$isHeaderSkiped) {
                $isHeaderSkiped = true;
                continue;
            }
            $arrInsert[] = $this->transform($row);
        }

        RefReminderCategory::insert($arrInsert);

        fclose($file);

        info("FINISH SEED " . self::class);
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
            "description" => $oldRow[1]
        ];
    }
}
