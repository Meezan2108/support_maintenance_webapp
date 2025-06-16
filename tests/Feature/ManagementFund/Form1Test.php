<?php

namespace Tests\Feature\ManagementFund;

use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\RefDivision;
use App\Models\RefPosition;
use App\Models\User;
use Tests\TestCase;

class Form1Test extends TestCase
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

        $response = $this->post(route('panel.trf.sub-form.form1.store', [
            'project_leader_type' => Proposal::TYPE_LEADER_INTERNAL,
            'proposal_type' => Proposal::TYPE_TRF,
            'ref_type_of_funding_id' => Proposal::TRF_ID,
            'project_title' => 'Test Project Title - ' . now(),
            'user_id' => $user->id,
            'researcher' => [
                'name' => $user->name,
                'nric' => 'NRIC-00012',
                'ref_division_id' => RefDivision::first()->id,
                'ref_position_id' => RefPosition::first()->id,
                'tel_no' => '62880903123',
                'fax_no' => '690981023',
                'email' => $user->email,
            ],
            'working_address' => 'working address',
            'institution' => 'Institution',
            'grade' => 'A',
            'keywords' => ['cocoa', 'test']
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

        $response = $this->put(route('panel.trf.sub-form.form1.update', ["form1" => $proposal]), [
            'project_leader_type' => Proposal::TYPE_LEADER_INTERNAL,
            'proposal_type' => Proposal::TYPE_TRF,
            'ref_type_of_funding_id' => Proposal::TRF_ID,
            'project_title' => 'Test Project Title - ' . now(),
            'user_id' => $user->id,
            'researcher' => [
                'name' => $user->name,
                'nric' => 'NRIC-00012',
                'ref_division_id' => RefDivision::first()->id,
                'ref_position_id' => RefPosition::first()->id,
                'tel_no' => '62880903123',
                'fax_no' => '690981023',
                'email' => $user->email,
            ],
            'working_address' => 'working address',
            'institution' => 'Institution',
            'grade' => 'A',
            'keywords' => ['cocoa', 'test']
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
