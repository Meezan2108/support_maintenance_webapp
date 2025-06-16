<?php

namespace Database\Seeders;

use App\Models\RefOutputType;
use Illuminate\Database\Seeder;

class RefOutputTypeSeeder extends Seeder
{

    protected $arrOutputType = [
        [
            "code" => "0001",
            "description" => "Product",
            "ref_target_kpi_category_id" => 14
        ],
        [
            "code" => "0002",
            "description" => "Technology",
            "ref_target_kpi_category_id" => 15
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefOutputType::truncate();


        foreach ($this->arrOutputType as $output_type) {
            RefOutputType::create([
                "code" => $output_type["code"],
                "description" => $output_type["description"],
                "ref_target_kpi_category_id" => $output_type["ref_target_kpi_category_id"]
            ]);
        }
    }
}
