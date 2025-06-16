<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\Approvement;
use App\Models\ImportedGermplasm;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ImportedGermplasmUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-update/Imported Germplasm.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];

        while (($row = fgetcsv($file)) !== false) {
            if ($hasHeader && !$isHeaderSkiped) {
                $isHeaderSkiped = true;
                continue;
            }

            $arrInsert = $this->transform($row);


            $germplasm = ImportedGermplasm::query()
                ->where([
                    'date' => $arrInsert['date'],
                    'no_germplasm' => $arrInsert['no_germplasm']
                ])->first();

            $status = $arrInsert["status"] == "Approve"
                ? Approvement::STATUS_APPROVED
                : Approvement::STATUS_REJECTED;

            if ($germplasm) {
                $germplasm->update([
                    "user_id" => 0,
                    "date" => $arrInsert["date"],
                    "no_germplasm" => $arrInsert["no_germplasm"],
                ]);

                $germplasm->kpiAchievement()->update([
                    "title" => Carbon::parse($germplasm->date)->format("Y-m-d"),
                    "user_id" => $germplasm->user_id,
                    "category_id" => ImportedGermplasm::CATEGORY_ID,
                    "date" => $germplasm->date,
                    "approval_status" => $status
                ]);
            } else {
                $germplasm = ImportedGermplasm::create([
                    "user_id" => 0,
                    "date" => $arrInsert["date"],
                    "no_germplasm" => $arrInsert["no_germplasm"],
                ]);

                $germplasm->kpiAchievement()->create([
                    "title" => Carbon::parse($germplasm->date)->format("Y-m-d"),
                    "user_id" => $germplasm->user_id,
                    "category_id" => ImportedGermplasm::CATEGORY_ID,
                    "date" => $germplasm->date,
                    "approval_status" => $status
                ]);
            }
        }

        fclose($file);

        info("FINISH SEED " . __CLASS__);
    }

    protected function transform($oldRow)
    {
        $oldRow = collect($oldRow);
        $oldRow = $oldRow->map(function ($item) {
            $item = EtlHelper::transformNull($item);
            return $item;
        });

        return [
            "date" => $oldRow[0], //
            "no_germplasm" => $oldRow[1], //
            "status" => $oldRow[2], //
        ];
    }
}
