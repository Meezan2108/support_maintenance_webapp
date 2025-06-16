<?php

namespace Database\Seeders;

use App\Actions\ManagementFund\CreateBenefits;
use App\Actions\ManagementFund\CreateExpensesEstimation;
use App\Actions\ManagementFund\CreateObjectives;
use App\Actions\ManagementFund\CreateProjectCollaboration;
use App\Actions\ManagementFund\CreateResearchApproach;
use App\Actions\ManagementFund\GenerateApplicationId;
use App\Helpers\EtlHelper;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ProposalResearchField;
use App\Models\refFORCategory;
use App\Models\RefResearchCluster;
use App\Models\RefResearchType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProposalDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Proposal::truncate();
        // ProposalResearchField::truncate();

        DB::transaction(function () {
            $proposal = $this->insertStep1();

            $proposal = $this->step2($proposal);
            $proposal = $this->step3($proposal);
            $proposal = $this->step4($proposal);
            $proposal = $this->step6($proposal);
            $proposal = $this->step7($proposal);
            $proposal = $this->step8($proposal);
            $proposal = $this->step9($proposal);
        });
    }

    protected function insertStep1()
    {
        $arrData = [
            'ref_type_of_funding_id' => 3,
            'project_leader_type' => 1,
            'proposal_type' => Proposal::TYPE_EXTERNAL_FUND,
            'project_title' => 'Project Title',
            'user_id' => 1,
            'working_address' => 'Working Address',
            'institution' => 'Institution',
            'grade' => 'Grade',
            'keywords' => ['Keyword 1', 'Keyword 2'],
        ];

        $proposal = Proposal::create($arrData);

        $researcherData = [
            'name' => 'researcher name',
            'nric' => '890071221',
            'ref_division_id' => 1,
            'ref_position_id' => 2,
            'tel_no' => '08903123',
            'fax_no' => '089091823',
            'email' => 'email@test.com',
        ];
        $proposal->researcher()->create($researcherData);

        return $proposal;
    }

    protected function step2($proposal)
    {
        $arrData = [
            'objectives' => [
                [
                    'description' => '<p>objectives 1</p>'
                ],
                [
                    'description' => '<p>objectives 2</p>'
                ],
            ],
            'ref_research_type_id' => 1,
            'ref_research_cluster_id' => 1,

            'ref_seo_category_id' => 46,
            'ref_seo_group_id' => 50,
            'ref_seo_area_id' => 9,

            'for_primary' => [
                'ref_for_category_id' => 26,
                'ref_for_group_id' => 24,
                'ref_for_area_id' => 15,
            ],

            'for_secondary' => [
                'ref_for_category_id' => 24,
                'ref_for_group_id' => 20,
                'ref_for_area_id' => 23,
            ]
        ];

        return (new CreateObjectives())->execute($proposal, $arrData);
    }

    protected function step3(Proposal $proposal)
    {
        $arrData = [
            'research_location' => '<p>Research Location</p>',
            'project_summary' => '<p>Project Summary</p>',
            'problem_statement' => '<p>Problem Statement</p>',

            'hypothesis' => '<p>Hypothesis</p>',
            'research_question' => '<p>Research Question</p>',
            'literature_review' => '<p>Literature Review</p>',

            'relevance_goverment_policy' => '<p>Relevance Goverment Policy</p>',
            'reference' => '<p>Reference</p>',
            'related_research' => '<p>Related Research</p>',
        ];

        $proposal->update($arrData);
        return $proposal;
    }

    protected function step4(Proposal $proposal)
    {
        $arrData = [
            'research_methodology' => '<p>Resesarch Methodology</p>',
            'risk_factor' => '<p>Risk Factor</p>',
            'risk_technical' => '<p>Risk Technical</p>',
            'risk_budget' => '<p>Risk Budget</p>',
            'risk_timing' => '<p>Risk Timing</p>',

            'schedule_start_date' => '2023-04',
            'schedule_duration' => 32,

            'activities' => [
                [
                    'activities' => 'activities 1',
                    'from' => '2023-04',
                    'to' => '2023-08',
                ],
                [
                    'activities' => 'activities 2',
                    'from' => '2023-09',
                    'to' => '2023-12',
                ],
                [
                    'activities' => 'activities 3',
                    'from' => '2024-01',
                    'to' => '2024-04',
                ],
                [
                    'activities' => 'activities 4',
                    'from' => '2024-05',
                    'to' => '2024-08',
                ],
                [
                    'activities' => 'activities 5',
                    'from' => '2024-09',
                    'to' => '2024-12',
                ],
                [
                    'activities' => 'activities 6',
                    'from' => '2025-01',
                    'to' => '2025-04',
                ],
                [
                    'activities' => 'Project Completion',
                    'from' => '2025-05',
                    'to' => '2025-08',
                ]
            ],

            'milestones' => [
                [
                    'activities' => 'activities 1',
                    'from' => '2023-04-30',
                ],
                [
                    'activities' => 'activities 2',
                    'from' => '2023-09-30',
                ],
                [
                    'activities' => 'activities 3',
                    'from' => '2024-01-30',
                ],
                [
                    'activities' => 'activities 4',
                    'from' => '2024-05-30',
                ],
                [
                    'activities' => 'activities 5',
                    'from' => '2024-09-30',
                ],
                [
                    'activities' => 'activities 6',
                    'from' => '2025-01-30',
                ],
                [
                    'activities' => 'Project Completion',
                    'from' => '2025-05-30',
                ]
            ],
        ];

        return (new CreateResearchApproach())->execute($proposal, $arrData);
    }

    protected function step6(Proposal $proposal)
    {
        $arrData = [
            'economic_contributions' => 'economic contribution',
            'output_expected' => [
                [
                    'ref_proposal_benefits_category_id' => 1,
                    'detail' => 'IPR',
                    'quantity' => 12,
                ],
                [
                    'ref_proposal_benefits_category_id' => 2,
                    'detail' => 'New/Improved Project',
                    'quantity' => 13,
                ],
                [
                    'ref_proposal_benefits_category_id' => 3,
                    'detail' => 'Method/Technique',
                    'quantity' => 14,
                ],
                [
                    'ref_proposal_benefits_category_id' => 4,
                    'detail' => 'New/Improved Product/device',
                    'quantity' => 15,
                ],
            ],

            'human_capital' => [
                [
                    'ref_proposal_benefits_category_id' => 5,
                    'detail' => 'Research staf with new specialization',
                    'quantity' => 16,
                ]
            ],
        ];

        return (new CreateBenefits())->execute($proposal, $arrData);
    }

    protected function step7(Proposal $proposal)
    {
        $arrdata = [
            'organizations' => [
                [
                    'name' => 'organization 1',
                    'role' => 'role 1',
                    'other' => 'other 1'
                ],
                [
                    'name' => 'organization 2',
                    'role' => 'role 2',
                    'other' => 'other 2'
                ]
            ],
            'industries' => [
                [
                    'name' => 'industries 1',
                    'role' => 'role 1',
                    'other' => 'other 1'
                ],
                [
                    'name' => 'industries 2',
                    'role' => 'role 2',
                    'other' => 'other 2'
                ]
            ],
            'project_leaders' => [
                [
                    'name' => 'project leader 1',
                    'organization' => 'organization 1',
                    'man_month' => '1'
                ],
                [
                    'name' => 'project leader 2',
                    'organization' => 'organization 2',
                    'man_month' => '2'
                ]
            ],
            'researchers' => [
                [
                    'name' => 'researcher 1',
                    'organization' => 'organization 1',
                    'man_month' => '1'
                ],
                [
                    'name' => 'researcher 2',
                    'organization' => 'organization 2',
                    'man_month' => '2'
                ]
            ],
            'staffs' => [
                [
                    'name' => 'staffs 1',
                    'organization' => 'staffs 1',
                    'man_month' => '1'
                ],
                [
                    'name' => 'staffs 2',
                    'organization' => 'staffs 2',
                    'man_month' => '2'
                ],
                [
                    'name' => 'staffs 3',
                    'organization' => 'staffs 3',
                    'man_month' => '3'
                ]
            ],
        ];

        return (new CreateProjectCollaboration())->execute($proposal, $arrdata);
    }

    protected function step8(Proposal $proposal)
    {
        $arrData = [
            'years' => [2023, 2024, 2025],

            'V21000' => [
                [
                    'description' => 'V21000 1',
                    'years' => [
                        200,
                        200,
                        200
                    ],
                ],
                [
                    'description' => 'V21000 2',
                    'years' => [
                        100,
                        100,
                        100
                    ],
                ]
            ],
            'V26000' => [
                [
                    'description' => 'V26000 1',
                    'years' => [
                        200,
                        200,
                        200
                    ],
                ],
                [
                    'description' => 'V26000 1',
                    'years' => [
                        100,
                        100,
                        100
                    ],
                ]
            ],
            'V28000' => [
                [
                    'description' => 'V28000 1',
                    'years' => [
                        200,
                        200,
                        200
                    ],
                ],
                [
                    'description' => 'V28000 1',
                    'years' => [
                        100,
                        100,
                        100
                    ],
                ]
            ],
            'V29000' => [
                [
                    'description' => 'V29000 1',
                    'years' => [
                        200,
                        200,
                        200
                    ],
                ],
                [
                    'description' => 'V29000 1',
                    'years' => [
                        100,
                        100,
                        100
                    ],
                ]
            ],
        ];

        return (new CreateExpensesEstimation)->execute($proposal, $arrData);
    }

    protected function step9(Proposal $proposal)
    {
        $arrCost = [
            'years' => [2023, 2024, 2025],
            'V11000' => [
                [
                    'description' => 'Salaried Personal',
                    'years' => [
                        100,
                        100,
                        100
                    ],
                ],
            ]
        ];

        /**
         * @var Proposal $proposal
         */
        $proposal = (new CreateExpensesEstimation)->execute($proposal, $arrCost);

        $proposal->approval_status = Approvement::STATUS_SUBMITED;
        $proposal->total_cost = $proposal->projectCost()
            ->sum('cost');

        $proposal->save();

        $proposal = (new GenerateApplicationId)->execute($proposal);

        return $proposal;
    }
}
