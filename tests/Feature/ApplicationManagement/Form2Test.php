<?php

namespace Tests\Feature\ApplicationManagement;

use App\Actions\ManagementFund\GenerateApplicationId;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ProposalResearcher;
use App\Models\RefDivision;
use App\Models\RefTypeOfFunding;
use App\Models\User;
use Tests\TestCase;

class Form2Test extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUpdateForm()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RMC]);
        $this->actingAs($user);

        $division = RefDivision::where('is_active', 1)
            ->first();

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'proposal_type' => Proposal::TYPE_EXTERNAL_FUND,
            'ref_type_of_funding_id' => RefTypeOfFunding::first()->id,
            'approval_status' => Approvement::STATUS_APPROVED
        ]);

        $researcher = ProposalResearcher::factory()->create([
            'proposal_id' => $proposal->id,
            'name' => $user->name,
            'email' => $user->email,
            'ref_division_id' => $division->id
        ]);

        $proposal = (new GenerateApplicationId)->execute($proposal);

        $response = $this->put(route('panel.list-of-approved.sub-form.form2.update', ["form2" => $proposal->id]), [
            'project_leader_type' => $proposal->project_leader_type,
            'proposal_type' => $proposal->proposal_type,

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
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
    }
}
