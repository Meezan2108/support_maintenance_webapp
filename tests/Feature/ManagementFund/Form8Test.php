<?php

namespace Tests\Feature\ManagementFund;

use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\RefProposalBenefitsItem;
use App\Models\User;
use Tests\TestCase;

class Form8Test extends TestCase
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

        $response = $this->post(route('panel.trf.sub-form.form8.store', [
            'project_leader_type' => Proposal::TYPE_LEADER_INTERNAL,
            'proposal_type' => Proposal::TYPE_TRF,

            'years' => [2023, 2024],
            'V21000' => [
                [
                    "description" => "description 1",
                    "years" => [2000, 3000]
                ]
            ],
            'V26000' => [
                [
                    "description" => "description 1",
                    "years" => [2000, 3000]
                ]
            ],
            'V28000' => [
                [
                    "description" => "description 1",
                    "years" => [2000, 3000]
                ]
            ],
            'V29000' => [
                [
                    "description" => "description 1",
                    "years" => [2000, 3000]
                ]
            ],
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

        $response = $this->put(route('panel.trf.sub-form.form8.update', ["form8" => $proposal]), [
            'project_leader_type' => $proposal->project_leader_type,

            'years' => [2023, 2024],
            'V21000' => [
                [
                    "description" => "description 1",
                    "years" => [2000, 3000]
                ]
            ],
            'V26000' => [
                [
                    "description" => "description 1",
                    "years" => [2000, 3000]
                ]
            ],
            'V28000' => [
                [
                    "description" => "description 1",
                    "years" => [2000, 3000]
                ]
            ],
            'V29000' => [],
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
