<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefPosition;
use App\Models\RefPslkm;
use App\Models\RefPslkmSub;
use Illuminate\Database\Seeder;

class PslkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefPslkm::truncate();
        RefPslkmSub::truncate();
        info("START");

        $file = fopen(storage_path("csv/crims-db-update/pslkm.csv"), 'r');

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

        fclose($file);

        foreach ($arrInsert as $data) {
            if ($data["no"]) {
                $refPslkm = RefPslkm::create([
                    "code" => $data["no"],
                    "description" => $data["pslkm"],
                    "status" => RefPslkm::STATUS_ACTIVE
                ]);
            }

            $refPslkm->sub()->create([
                "code" => $data["pslkmsub_code"],
                "description" => $data["pslkmsub_description"],
                "status" => RefPslkmSub::STATUS_ACTIVE
            ]);
        }

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
            "no" => $oldRow[0],
            "pslkm" => $oldRow[1],
            "pslkmsub_code" => $oldRow[2],
            "pslkmsub_description" => $oldRow[3],
        ];
    }
}
