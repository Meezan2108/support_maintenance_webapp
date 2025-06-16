<?php

namespace Tests\Feature\ManagementFund;

use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\User;
use Tests\TestCase;

class Form4Test extends TestCase
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

        $response = $this->post(route('panel.trf.sub-form.form4.store', [
            'project_leader_type' => Proposal::TYPE_LEADER_INTERNAL,
            'proposal_type' => Proposal::TYPE_TRF,
            'research_methodology' => 'Research Methodology',
            'risk_factor' => 'medium',
            'risk_technical' => 'medium',
            'risk_budget' => 'medium',
            'risk_timing' => 'medium',

            'schedule_start_date' => date('Y-m'),
            'schedule_duration' => 24,

            'activities' => [
                [
                    'activities' => 'Act 1',
                    'from' => date('Y-m'),
                    'to' => date('Y-m'),
                ]
            ],

            'milestones' => [
                [
                    'activities' => 'Milestone 1',
                    'from' => date('Y-m-d'),
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

        $response = $this->put(route('panel.trf.sub-form.form4.update', ["form4" => $proposal]), [
            'project_leader_type' => $proposal->project_leader_type,
            'research_methodology' => 'Research Methodology',
            'risk_factor' => 'medium',
            'risk_technical' => 'medium',
            'risk_budget' => 'medium',
            'risk_timing' => 'medium',

            'schedule_start_date' => date('Y-m'),
            'schedule_duration' => 24,

            'activities' => [
                [
                    'activities' => 'Act 1',
                    'from' => date('Y-m'),
                    'to' => date('Y-m'),
                ]
            ],

            'milestones' => [
                [
                    'activities' => 'Milestone 1',
                    'from' => date('Y-m-d'),
                ]
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
