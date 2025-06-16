<?php

namespace App\Actions\Test\ManagementFund;

use App\Actions\ManagementFund\CreateResearchApproach;
use App\Actions\ManagementFund\GenerateApplicationId;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ProposalResearcher;
use App\Models\RefDivision;
use App\Models\RefTypeOfFunding;
use App\Models\User;

class CreateProposalTest
{
    public function execute(User $user, $type = Proposal::TYPE_TRF)
    {
        $division = RefDivision::where('is_active', 1)
            ->first();

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'proposal_type' => $type,
            'ref_type_of_funding_id' => $type == Proposal::TYPE_TRF
                ? Proposal::TRF_ID
                : RefTypeOfFunding::first()->id,
            'approval_status' => Approvement::STATUS_APPROVED
        ]);

        $researcher = ProposalResearcher::factory()->create([
            'proposal_id' => $proposal->id,
            'name' => $user->name,
            'email' => $user->email,
            'ref_division_id' => $division->id
        ]);

        $proposal = (new GenerateApplicationId)->execute($proposal);

        $arrData = [
            'research_methodology' => '<p>Research Methodology</p>',
            'risk_factor' => 'medium',
            'risk_technical' => 'medium',
            'risk_budget' => 'medium',
            'risk_timing' => 'medium',

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
                    'from' => '2023-08-30',
                ],
                [
                    'activities' => 'activities 2',
                    'from' => '2023-12-30',
                ],
                [
                    'activities' => 'activities 3',
                    'from' => '2024-04-30',
                ],
                [
                    'activities' => 'activities 4',
                    'from' => '2024-08-30',
                ],
                [
                    'activities' => 'activities 5',
                    'from' => '2024-12-30',
                ],
                [
                    'activities' => 'activities 6',
                    'from' => '2025-04-30',
                ],
                [
                    'activities' => 'Project Completion',
                    'from' => '2025-08-30',
                ]
            ],
        ];

        $proposal = (new CreateResearchApproach)->execute($proposal, $arrData);

        return $proposal;
    }
}
