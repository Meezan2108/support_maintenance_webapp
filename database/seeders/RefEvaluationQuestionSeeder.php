<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefEvaluationAnswerCategory;
use App\Models\RefEvaluationQuestion;
use App\Models\RefSeoArea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefEvaluationQuestionSeeder extends Seeder
{
    protected $data = [
        [
            'category' => 1,
            'description' => 'Does the proposed project fall under the LKM’s research priority area?',
            'options' => ['Yes', 'No'],
            'order' => 1
        ],
        [
            'category' => 1,
            'description' => 'Does the applicant have the professional quantifications and team members (if applicable) necessary for satisfactory performance of the proposed activity?',
            'options' => ['Yes', 'No'],
            'order' => 2
        ],
        [
            'category' => 1,
            'description' => 'Is there other funding sources to supplement the fund provided by TRF?',
            'options' => ['Yes', 'No'],
            'order' => 3
        ],
        [
            'category' => 1,
            'description' => 'Is the applicant currently heading any research projects? If yes, please state source and number.',
            'options' => ['Yes', 'No'],
            'order' => 4
        ],

        [
            'category' => 2,
            'description' => 'Viability of research objectives',
            'options' => ['Inadequate', 'Acceptable', 'Very Good'],
            'order' => 1
        ],
        [
            'category' => 2,
            'description' => 'Appropriateness of research methodology',
            'options' => ['Inadequate', 'Acceptable', 'Very Good'],
            'order' => 2
        ],
        [
            'category' => 2,
            'description' => 'Cost effectiveness',
            'options' => ['Inadequate', 'Acceptable', 'Very Good'],
            'order' => 3
        ],
        [
            'category' => 2,
            'description' => 'Commercialization potential',
            'options' => ['Inadequate', 'Acceptable', 'Very Good'],
            'order' => 4
        ],

        [
            'category' => 3,
            'description' => 'Technical',
            'options' => ['Low', 'Medium', 'High'],
            'order' => 1
        ],
        [
            'category' => 3,
            'description' => 'Financial',
            'options' => ['Low', 'Medium', 'High'],
            'order' => 2
        ],
        [
            'category' => 3,
            'description' => 'Timeline',
            'options' => ['Low', 'Medium', 'High'],
            'order' => 3
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefEvaluationQuestion::truncate();
        info("START SEED " . __CLASS__);

        foreach ($this->data as $arrInsert) {
            RefEvaluationQuestion::create($arrInsert);
        }

        info("FINISH SEED " . __CLASS__);
    }
}
