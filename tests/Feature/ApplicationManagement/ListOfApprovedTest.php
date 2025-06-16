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

class ListOfApprovedTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RMC]);
        $this->actingAs($user);

        $response = $this->get(route('panel.list-of-approved.index'));

        $response->assertStatus(200);
    }

    public function testCreatePage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RMC]);
        $this->actingAs($user);

        $response = $this->get(route('panel.list-of-approved.create'));

        $response->assertStatus(200);
    }

    public function testShowPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

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

        $userAuth = User::factory()->create();
        $userAuth->assignRole([User::ROLE_RMC]);

        $this->actingAs($userAuth);

        $response = $this->get(route('panel.list-of-approved.show', ["proposal" => $proposal->id]));

        $response->assertStatus(200);
    }

    public function testEditPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

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

        $userAuth = User::factory()->create();
        $userAuth->assignRole([User::ROLE_RMC]);

        $this->actingAs($userAuth);

        $response = $this->get(route('panel.list-of-approved.show', ["proposal" => $proposal->id]));

        $response->assertStatus(200);
    }
}
