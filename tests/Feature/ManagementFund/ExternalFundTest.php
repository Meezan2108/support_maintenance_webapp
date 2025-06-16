<?php

namespace Tests\Feature\ManagementFund;

use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ProposalResearcher;
use App\Models\RefDivision;
use App\Models\RefTypeOfFunding;
use App\Models\User;
use Tests\TestCase;

class ExternalFundTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $response = $this->get(route('panel.external-fund.index'));

        $response->assertStatus(200);
    }

    public function testCreatePage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $response = $this->get(route('panel.external-fund.create'));

        $response->assertStatus(200);
    }

    public function testShowPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'proposal_type' => Proposal::TYPE_EXTERNAL_FUND,
            'ref_type_of_funding_id' => RefTypeOfFunding::first()->id,
        ]);

        $response = $this->get(route('panel.external-fund.show', ["proposal" => $proposal->id]));

        $response->assertStatus(200);
    }

    public function testEditPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'proposal_type' => Proposal::TYPE_EXTERNAL_FUND,
            'ref_type_of_funding_id' => RefTypeOfFunding::first()->id,
        ]);

        $response = $this->get(route('panel.external-fund.edit', ["proposal" => $proposal->id]));

        $response->assertStatus(200);
    }

    public function testDelete()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'proposal_type' => Proposal::TYPE_EXTERNAL_FUND,
            'ref_type_of_funding_id' => RefTypeOfFunding::first()->id,
            'approval_status' => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->delete(route('panel.external-fund.delete', ["proposal" => $proposal]));

        $this->assertSoftDeleted(Proposal::class, ['id' => $proposal->id]);
    }

    public function testDownload()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'approval_status' => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->get(route('panel.external-fund.download', ["proposal" => $proposal]));

        $response->assertStatus(200);
    }


    public function testGetCommentPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $division = RefDivision::where('is_active', 1)
            ->first();

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'proposal_type' => Proposal::TYPE_EXTERNAL_FUND,
            'ref_type_of_funding_id' => RefTypeOfFunding::first()->id,
            'approval_status' => Approvement::STATUS_SUBMITED
        ]);

        $researcher = ProposalResearcher::factory()->create([
            'proposal_id' => $proposal->id,
            'name' => $user->name,
            'email' => $user->email,
            'ref_division_id' => $division->id
        ]);

        $userAuth = User::factory()->create([
            'ref_division_id' => $division->id
        ]);
        $userAuth->assignRole([User::ROLE_DIVISION_DIRECTOR]);

        $this->actingAs($userAuth);
        $response = $this->get(route('panel.external-fund.comment', ["proposal" => $proposal]));

        $response->assertStatus(200);
    }

    public function testUpdateComment()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $division = RefDivision::where('is_active', 1)
            ->first();

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'proposal_type' => Proposal::TYPE_EXTERNAL_FUND,
            'ref_type_of_funding_id' => RefTypeOfFunding::first()->id,
            'approval_status' => Approvement::STATUS_SUBMITED
        ]);

        $researcher = ProposalResearcher::factory()->create([
            'proposal_id' => $proposal->id,
            'name' => $user->name,
            'email' => $user->email,
            'ref_division_id' => $division->id
        ]);

        $userAuth = User::factory()->create([
            'ref_division_id' => $division->id
        ]);
        $userAuth->assignRole([User::ROLE_DIVISION_DIRECTOR]);

        $this->actingAs($userAuth);
        $response = $this->put(route('panel.external-fund.comment', ["proposal" => $proposal]), [
            'identification' => 'Identification Comment',
            'objectives' => 'Objectives Comment',
            'research_background' => 'Research Background Comment',
            'research_approach' => 'Research Approach Comment',
            'project_schedule' => 'Project Schedule Comment',
            'benefits' => 'Benefits Comment',
            'research_collabration' => 'Research Collaboration Comment',
            'expenses_estimation' => 'Expenses Estimation Comment',
            'project_cost' => 'Project Cost Comment',
            'last' => true
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
