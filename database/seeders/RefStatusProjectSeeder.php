<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefStatusProject;
use App\Models\RefStatusProposal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefStatusProjectSeeder extends Seeder
{
    protected $arrOutputType = [
        [
            "code" => "1",
            "description" => "Proposal",
        ],
        [
            "code" => "2",
            "description" => "On-Going",
        ],
        [
            "code" => "3",
            "description" => "Completed",
        ],
        [
            "code" => "4",
            "description" => "Extended",
        ],
        [
            "code" => "5",
            "description" => "Terminated",
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefStatusProject::truncate();


        foreach ($this->arrOutputType as $output_type) {
            RefStatusProject::create([
                "code" => $output_type["code"],
                "description" => $output_type["description"],
            ]);
        }
    }
}
