<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefEvaluationAnswerCategory;
use App\Models\RefEvaluationQuestion;
use App\Models\RefProposalBenefitsCategory;
use App\Models\RefProposalBenefitsItem;
use App\Models\RefSeoArea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefProposalBenefitsItemSeeder extends Seeder
{
    protected $data = [
        [
            'category' => 1,
            'type' => '',
            'description' => 'IPR',
            'order' => 1
        ],
        [
            'category' => 1,
            'type' => '',
            'description' => 'New/improved process',
            'order' => 2
        ],
        [
            'category' => 1,
            'type' => '',
            'description' => 'Method/technique',
            'order' => 3
        ],
        [
            'category' => 1,
            'type' => '',
            'description' => 'New/improved product/device',
            'order' => 4
        ],

        [
            'category' => 2,
            'type' => '',
            'description' => 'Research staff with new specialization',
            'order' => 1
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefProposalBenefitsItem::truncate();
        info("START SEED " . __CLASS__);

        foreach ($this->data as $arrInsert) {
            RefProposalBenefitsItem::create($arrInsert);
        }

        info("FINISH SEED " . __CLASS__);
    }
}
