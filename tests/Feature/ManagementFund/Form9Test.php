<?php

namespace Tests\Feature\ManagementFund;

use App\Actions\ManagementFund\GenerateApplicationId;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\User;
use Tests\TestCase;

class Form9Test extends TestCase
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

        $response = $this->post(route('panel.trf.sub-form.form9.store', [
            'project_leader_type' => Proposal::TYPE_LEADER_INTERNAL,
            'proposal_type' => Proposal::TYPE_TRF,

            'years' => [2023, 2024],
            'cost_salaried' => [
                "years" => [2000, 3000]
            ],
            'save_as_draft' => true
        ]));

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
    public function testStoreFormWithStatusSubmitted()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'approval_status' => Approvement::STATUS_TEMP
        ]);

        $response = $this->post(route('panel.trf.sub-form.form9.store', [
            'project_leader_type' => Proposal::TYPE_LEADER_INTERNAL,
            'proposal_type' => Proposal::TYPE_TRF,

            'years' => [2023, 2024],
            'cost_salaried' => [
                "years" => [2000, 3000]
            ],
            'approval_status' => true
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['report']);
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

        $proposal = (new GenerateApplicationId)->execute($proposal);

        $response = $this->put(route('panel.trf.sub-form.form9.update', ["form9" => $proposal]), [
            'project_leader_type' => $proposal->project_leader_type,

            'years' => [2023, 2024],
            'cost_salaried' => [
                "years" => [2000, 3000]
            ],
            'approval_status' => true
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
