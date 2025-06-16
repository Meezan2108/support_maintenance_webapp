<?php

namespace Tests\Feature\ManagementFund;

use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\RefProposalBenefitsItem;
use App\Models\User;
use Tests\TestCase;

class Form6Test extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStoreForm()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'approval_status' => Approvement::STATUS_TEMP
        ]);

        $outputExpected = RefProposalBenefitsItem::where('category', 1)->get();
        $humanCapital = RefProposalBenefitsItem::where('category', 2)->get();

        $response = $this->post(route('panel.trf.sub-form.form6.store', [
            'project_leader_type' => Proposal::TYPE_LEADER_INTERNAL,
            'proposal_type' => Proposal::TYPE_TRF,
            'economic_contributions' => [
                [
                    'description' => 'Economic Contributions 1'
                ],
                [
                    'description' => 'Economic Contributions 2'
                ]
            ],
            'output_expected' => $outputExpected->map(function ($item) {
                return [
                    'ref_proposal_benefits_category_id' => $item->id,
                    'detail' => 'Detail ' . $item->id,
                    'quantity' => 12,
                ];
            })->toArray(),

            'human_capital' => $humanCapital->map(function ($item) {
                return [
                    'ref_proposal_benefits_category_id' => $item->id,
                    'detail' => 'Detail ' . $item->id,
                    'quantity' => 12,
                ];
            })->toArray(),
        ]));

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }

    public function testUpdateForm()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'approval_status' => Approvement::STATUS_AMEND
        ]);

        $outputExpected = RefProposalBenefitsItem::where('category', 1)->get();
        $humanCapital = RefProposalBenefitsItem::where('category', 2)->get();

        $response = $this->put(route('panel.trf.sub-form.form6.update', ["form6" => $proposal]), [
            'project_leader_type' => $proposal->project_leader_type,
            'economic_contributions' => [
                [
                    'description' => 'Economic Contributions 1'
                ],
                [
                    'description' => 'Economic Contributions 2'
                ]
            ],
            'output_expected' => $outputExpected->map(function ($item) {
                return [
                    'ref_proposal_benefits_category_id' => $item->id,
                    'detail' => 'Detail ' . $item->id,
                    'quantity' => 12,
                ];
            })->toArray(),

            'human_capital' => $humanCapital->map(function ($item) {
                return [
                    'ref_proposal_benefits_category_id' => $item->id,
                    'detail' => 'Detail ' . $item->id,
                    'quantity' => 12,
                ];
            })->toArray(),
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
