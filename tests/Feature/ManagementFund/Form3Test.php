<?php

namespace Tests\Feature\ManagementFund;

use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\User;
use Tests\TestCase;

class Form3Test extends TestCase
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

        $response = $this->post(route('panel.trf.sub-form.form3.store', [
            'project_leader_type' => Proposal::TYPE_LEADER_INTERNAL,
            'proposal_type' => Proposal::TYPE_TRF,
            'research_location' => 'research location',
            'project_summary' => 'Project Summary',
            'problem_statement' => 'Problem Statement',

            'hypothesis' => 'Hypothesis',
            'research_question' => 'Research Question',
            'literature_review' => 'Literature Review',

            'relevance_goverment_policy' => 'Relevance Goverment Policy',
            'reference' => 'Reference',
            'related_research' => 'Related Research',
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

        $response = $this->put(route('panel.trf.sub-form.form3.update', ["form3" => $proposal]), [
            'project_leader_type' => $proposal->project_leader_type,
            'research_location' => 'research location',
            'project_summary' => 'Project Summary',
            'problem_statement' => 'Problem Statement',

            'hypothesis' => 'Hypothesis',
            'research_question' => 'Research Question',
            'literature_review' => 'Literature Review',

            'relevance_goverment_policy' => 'Relevance Goverment Policy',
            'reference' => 'Reference',
            'related_research' => 'Related Research',
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
