<?php

namespace Database\Seeders;

use App\Models\RefTargetKpiPeriod;
use Illuminate\Database\Seeder;

class RefTargetKpiPeriodSeeder extends Seeder
{
    protected $data = [
        [
            "code" => 1,
            "description" => "1st Half",
            "type" => "half_year",
            "options" => [
                "start_month" => 1,
                "end_month" => 6
            ]
        ],
        [
            "code" => 2,
            "description" => "2nd Half",
            "type" => "half_year",
            "options" => [
                "start_month" => 7,
                "end_month" => 12
            ]
        ],
        [
            "code" => 3,
            "description" => "Q1",
            "type" => "quarter",
            "options" => [
                "start_month" => 1,
                "end_month" => 3
            ]
        ],
        [
            "code" => 4,
            "description" => "Q2",
            "type" => "quarter",
            "options" => [
                "start_month" => 4,
                "end_month" => 6
            ]
        ],
        [
            "code" => 5,
            "description" => "Q3",
            "type" => "quarter",
            "options" => [
                "start_month" => 7,
                "end_month" => 9
            ]
        ],
        [
            "code" => 6,
            "description" => "Q4",
            "type" => "quarter",
            "options" => [
                "start_month" => 10,
                "end_month" => 12
            ]
        ],
        [
            "code" => 7,
            "description" => "Year",
            "type" => "year",
            "options" => [
                "start_month" => 1,
                "end_month" => 12
            ]
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        RefTargetKpiPeriod::truncate();
        info("START SEED " . __CLASS__);

        foreach ($this->data as $item) {
            RefTargetKpiPeriod::create($item);
        }

        info("FINISH SEED " . __CLASS__);
    }
}
