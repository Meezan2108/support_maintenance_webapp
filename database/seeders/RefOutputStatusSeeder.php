<?php

namespace Database\Seeders;

use App\Models\RefOutputStatus;
use Illuminate\Database\Seeder;

class RefOutputStatusSeeder extends Seeder
{
    protected $arrOutputStatus = [
        [
            "code" => "0001",
            "description" => "Pre-commercialization",
        ],
        [
            "code" => "0002",
            "description" => "Ready to be commercialised",
        ],
        [
            "code" => "0003",
            "description" => "Commercialized",
        ],
        [
            "code" => "0004",
            "description" => "Packaging",
        ],
        [
            "code" => "0005",
            "description" => "Formulation",
        ],
        [
            "code" => "0006",
            "description" => "Completed",
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefOutputStatus::truncate();


        foreach ($this->arrOutputStatus as $output_status) {
            RefOutputStatus::create([
                "code" => $output_status["code"],
                "description" => $output_status["description"],
            ]);
        }
    }
}
