<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\refBahagian;
use App\Models\RefDivision;
use Illuminate\Database\Seeder;

class RefDivisionSeeder extends Seeder
{
    protected $arrDivisionEn = [
        [
            "code2" => 101,
            "description" => "Directorat"
        ],
        [
            "code2" => 102,
            "description" => "Information Technology Service Unit"
        ],
        [
            "code2" => 103,
            "description" => "Management Service Division"
        ],
        [
            "code2" => 111,
            "description" => "Upstream Technology Division"
        ],
        [
            "code2" => 113,
            "description" => "Downstream Technology Division"
        ],
        [
            "code2" => 121,
            "description" => "Market & Economic Development Division"
        ],
        [
            "code2" => 122,
            "description" => "Regulatory & Quality Control Division"
        ],
        [
            "code2" => 112,
            "description" => "Biotechnology Division"
        ],
        [
            "code2" => 112,
            "description" => "Technology Transfer & Extension Division"
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefDivision::truncate();
        info("START");

        $file = fopen(storage_path("csv/crims-db-v1/refBahagian.csv"), 'r');

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

        RefDivision::insert($arrInsert);

        fclose($file);

        info("FINISH SEED refBahagian");
    }

    public function transform(array $oldRow)
    {
        $oldRow = collect($oldRow);
        $oldRow = $oldRow->map(function ($item) {
            $item = trim($item);
            return EtlHelper::transformNull($item);
        });

        $listDivisionEn = collect($this->arrDivisionEn);

        $divisionEn = $listDivisionEn->firstWhere("code2", $oldRow[2]);

        return [
            "code" => $oldRow[0],
            "code2" => $oldRow[2],
            "description" => $divisionEn["description"] ?? '',
            "is_active" => isset($divisionEn["description"]) ? 1 : 0,
            "description_malay" => $oldRow[1],
        ];
    }
}
