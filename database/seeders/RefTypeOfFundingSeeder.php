<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefTypeOfFunding;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefTypeOfFundingSeeder extends Seeder
{
    protected $headers;

    protected $data = [
        [
            "type" => 1,
            "description" => "Tabung Penyelidikan Sementara (TRF)",
            "ref_target_kpi_category_id" => 27
        ],
        [
            "type" => 2,
            "description" => "Fundamental Research Grant Scheme (FRGS)",
            "ref_target_kpi_category_id" => 25
        ],
        [
            "type" => 2,
            "description" => "Malaysia Social Innovation (MySI) fund",
            "ref_target_kpi_category_id" => 26
        ],
        [
            "type" => 2,
            "description" => "Pembangunan",
            "ref_target_kpi_category_id" => 28
        ],
        [
            "type" => 2,
            "description" => "Antarabangsa",
            "ref_target_kpi_category_id" => 29
        ],
        [
            "type" => 2,
            "description" => "Tempatan",
            "ref_target_kpi_category_id" => 30
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefTypeOfFunding::truncate();
        info("START SEED " . __CLASS__);

        DB::unprepared('SET IDENTITY_INSERT ' . (new RefTypeOfFunding())->getTable() . ' ON');

        $file = fopen(storage_path("csv/crims-db-v1/refProjek.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];
        while (($row = fgetcsv($file)) !== FALSE) {
            if ($hasHeader && !$isHeaderSkiped) {
                $this->headers = $row;
                $isHeaderSkiped = true;
                continue;
            }
            $arrInsert = $this->transform($row);

            RefTypeOfFunding::create($arrInsert);
        }

        fclose($file);
        info("FINISH SEED " . __CLASS__);
    }

    protected function transform($oldRow)
    {
        $oldRow = collect($oldRow);

        $dataKey = $oldRow->map(function ($item, $index) {
            return [
                "key" => EtlHelper::transformNull($this->headers[$index]),
                "data" => EtlHelper::transformNull($item)
            ];
        });

        $originalData = [];
        foreach ($dataKey as $data) {
            $originalData[$data["key"]] = $data["data"];
        }

        return [
            "id" => $originalData['Kod'],
            "code" => $originalData['Code'],
            "type" => $originalData['Description'] == "Temporary Research Fund (TRF)" ? 1 : 2,
            "description" => $originalData['Description'],
            "created_at" => now(),
            "updated_at" => now(),
            "original_data" => $originalData,
            "original_id" => $originalData['Kod']
        ];
    }
}
