<?php

namespace Database\Seeders;

use App\Models\RefReportEopBenefitsGroup;
use App\Models\RefReportEopBenefitsQuestion;
use App\Models\RefReportEopBenefitsSection;
use Illuminate\Database\Seeder;

class RefReportEopBenefitsSeeder extends Seeder
{
    protected $data = [
        [
            'title' => 'Direct Outputs of the Project',
            'description' => 'Please describe as specifically as possible the outputs achieved and provide an assessment of their significant to users',
            'order' => 1,
            'section' => [
                [
                    'title' => 'Technical contribution of the project',
                    'description' => '',
                    'order' => 1,
                    'question' => [
                        [
                            'content' => 'What was the achieved direct output of the project, For basic oriented research projects?',
                            'type' => 'multitext',
                            'options' => ['Algorithm', 'Structure', 'Data'],
                            'order' => 1,
                            'rules' => []
                        ],
                        [
                            'content' => 'What was the achieved direct output of the project, For applied research (technology development) projects',
                            'type' => 'multitext',
                            'options' => ['Method/technique', 'Demonstrator/prototype', 'Product/component', 'Process', 'Software'],
                            'order' => 2,
                            'rules' => []
                        ],
                        [
                            'content' => 'How would you characterise the quality of this output?',
                            'type' => 'multi',
                            'options' => ['Significant breakthrough', 'Major improvement', 'Minor improvement'],
                            'order' => 3,
                            'rules' => []
                        ]
                    ]
                ],
                [
                    'title' => 'Contribution of the project to knowledge',
                    'description' => '',
                    'order' => 2,
                    'question' => [
                        [
                            'content' => 'How has the output of the project been documented?',
                            'type' => 'multitext',
                            'options' => ['Detailed project report', 'Product/process specification documents'],
                            'order' => 1,
                            'rules' => []
                        ],
                        [
                            'content' => 'Did the project create an intellectual property stock?',
                            'type' => 'multi',
                            'options' => ['Patent obtained', 'Patent pending', 'Patent application will be filed', 'Copyright'],
                            'order' => 2,
                            'rules' => []
                        ],
                        [
                            'content' => 'What publications are available?',
                            'type' => 'multitextvalue',
                            'options' => [
                                'options' => ['Articles (s) in scientific publications', 'Paper(s) delivered at conferences/seminars', 'Book'],
                                'label' => 'How Many:'
                            ],
                            'order' => 3,
                            'rules' => []
                        ],
                        [
                            'content' => 'How significant are citations of the results?',
                            'type' => 'multivalue_with_visibility_status',
                            'options' => [
                                'options' => ['Citations in national publications', 'Citations in international publications', 'None yet', 'Not known'],
                                'visibility_value' => [1, 1, 0, 0],
                                'label' => 'How Many:'
                            ],
                            'order' => 4,
                            'rules' => []
                        ],
                    ]
                ],
            ]
        ],
        [
            'title' => 'Organisational Outcomes of the Project',
            'description' => 'Please describe as specifically as possible the organisational benefits arising from the project and provide an assessment of their significance',
            'order' => 2,
            'section' => [
                [
                    'title' => 'Contribution of the project to expertise development',
                    'description' => '',
                    'order' => 1,
                    'question' => [
                        [
                            'content' => 'How did the project contribute to expertise?',
                            'type' => 'multitextvalue',
                            'options' => [
                                'options' => ['PhD degrees', 'MSc degrees', 'Research staff with new specialty'],
                                'label' => 'How Many:'
                            ],
                            'order' => 1,
                            'rules' => []
                        ],
                        [
                            'content' => 'How significant is this expertise?',
                            'type' => 'multi',
                            'options' => ['One of the key areas of priority for Malaysia', 'An important area, but not a priority one'],
                            'order' => 2,
                            'rules' => []
                        ],
                    ]
                ],
                [
                    'title' => 'Economic contribution of the project?',
                    'description' => '',
                    'order' => 2,
                    'question' => [
                        [
                            'content' => 'How has the economic contribution of the project materialised?',
                            'type' => 'multitext',
                            'options' => ['Sales of manufactured product/equipment', 'Royalties from licensing', 'Cost savings', 'Time savings'],
                            'order' => 1,
                            'rules' => []
                        ],
                        [
                            'content' => 'How important is this economic contribution?',
                            'type' => 'multivalue',
                            'options' => [
                                'options' => ['High economic contribution', 'Medium economic contribution', 'Low economic contribution'],
                                'label' => 'Value: RM '
                            ],
                            'order' => 2,
                            'rules' => []
                        ],
                        [
                            'content' => 'When has this economic contribution materialised?',
                            'type' => 'multi',
                            'options' => ['Already materialised', 'Within months of project completion', 'Within three years of project completion', 'Expected in three years or more', 'Unknown'],
                            'order' => 3,
                            'rules' => []
                        ],
                    ]
                ],
                [
                    'title' => 'Infrastructural contribution of the project',
                    'description' => '',
                    'order' => 3,
                    'question' => [
                        [
                            'content' => 'What infrastructural contribution has the project had?',
                            'type' => 'multitextvalue2',
                            'options' => [
                                'options' => ['New equipment', 'New/improved facility', 'New information networks'],
                                'label' => [
                                    'Value RM:',
                                    'Investment RM:'
                                ]
                            ],
                            'order' => 1,
                            'rules' => []
                        ],
                        [
                            'content' => 'How significant is this infrastructural contribution for the organisation?',
                            'type' => 'multi',
                            'options' => ['Not significant/does not leverage other projects', 'Moderately significant', 'Very significant/significantly leverages other projects'],
                            'order' => 2,
                            'rules' => []
                        ],
                    ]
                ],
                [
                    'title' => 'Contribution of the project to the organisation’s reputation',
                    'description' => '',
                    'order' => 4,
                    'question' => [
                        [
                            'content' => 'How has the project contributed to increasing the reputation of the organisation',
                            'type' => 'multitext',
                            'options' => [
                                'Recognition as a Center of Excellence',
                                'National award',
                                'International award',
                                'Demand for advisory services',
                                'Invitations to give speeches on conferences',
                                'Visits from other organisations',
                            ],
                            'order' => 1,
                            'rules' => []
                        ],
                        [
                            'content' => 'How important is the project’s contribution to the organisation’s reputation?',
                            'type' => 'multi',
                            'options' => ['Not significant', 'Moderately significant', 'Very significant'],
                            'order' => 2,
                            'rules' => []
                        ],
                    ]
                ],
            ]
        ],
        [
            'title' => 'National Impacts of the Project',
            'description' => 'If known at this point in time, please describe as specifically as possible the potential sectoral/national benefits arising from the project and provide an assessment of their significance',
            'order' => 3,
            'section' => [
                [
                    'title' => 'Contribution of the project to organisational linkages',
                    'description' => '',
                    'order' => 1,
                    'question' => [
                        [
                            'content' => 'Which kinds of linkages did the project create?',
                            'type' => 'multi',
                            'options' => [
                                'Domestic industry linkages',
                                'International industry linkages',
                                'Linkages with domestic research institutions, universities',
                                'Linkages with international research institutions, universities'
                            ],
                            'order' => 1,
                            'rules' => []
                        ],
                        [
                            'content' => 'What is the nature of the linkages?',
                            'type' => 'multitext',
                            'options' => [
                                'Staff exchanges',
                                'Inter-organisational project team',
                                'Research contract with a commercial client',
                                'Informal consultation'
                            ],
                            'order' => 2,
                            'rules' => []
                        ],
                    ]
                ],
                [
                    'title' => 'Social-economic contribution of the project',
                    'description' => '',
                    'order' => 2,
                    'question' => [
                        [
                            'content' => 'Who are the direct customer/beneficiaries of the project output?',
                            'type' => 'table',
                            'options' => [
                                'Customers/beneficiaries:',
                                'Number:'
                            ],
                            'order' => 1,
                            'rules' => []
                        ],
                        [
                            'content' => 'How has/will the socio-economic contribution of the project materialised?',
                            'type' => 'multitext',
                            'options' => [
                                'Improvements in health',
                                'Improvements in safety',
                                'Improvements in the environment',
                                'Improvements in energy consumption/supply',
                                'Improvements in international relations',
                            ],
                            'order' => 2,
                            'rules' => []
                        ],
                        [
                            'content' => 'How important is this socio-economic contribution?',
                            'type' => 'multi',
                            'options' => [
                                'High social contribution',
                                'Medium social contribution',
                                'Low social contribution',
                            ],
                            'order' => 3,
                            'rules' => []
                        ],
                        [
                            'content' => 'When has/will this social contribution materialised?',
                            'type' => 'multi',
                            'options' => [
                                'Already materialised',
                                'Within three years of project completion',
                                'Expected in three years or more',
                                'Unknown',
                            ],
                            'order' => 4,
                            'rules' => []
                        ],
                    ]
                ],
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
        RefReportEopBenefitsQuestion::truncate();
        RefReportEopBenefitsSection::truncate();
        RefReportEopBenefitsGroup::truncate();

        info("START SEED " . __CLASS__);

        foreach ($this->data as $arrInsert) {
            $sections = $arrInsert['section'];
            unset($arrInsert['section']);
            $group = RefReportEopBenefitsGroup::create($arrInsert);

            foreach ($sections as $sectionInsert) {
                $questions = $sectionInsert['question'];
                unset($sectionInsert['question']);
                $section = $group->section()->create($sectionInsert);

                foreach ($questions as $question) {
                    $section->question()->create($question);
                }
            }
        }

        info("FINISH SEED " . __CLASS__);
    }
}
