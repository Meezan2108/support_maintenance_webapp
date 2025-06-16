<?php

namespace Tests\Feature\ManagementFund;

use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\RefProposalBenefitsItem;
use App\Models\User;
use Tests\TestCase;

class Form7Test extends TestCase
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

        $response = $this->post(route('panel.trf.sub-form.form7.store', [
            'project_leader_type' => Proposal::TYPE_LEADER_INTERNAL,
            'proposal_type' => Proposal::TYPE_TRF,

            'organizations' => [
                [
                    "name" => "Organization 1",
                    "role" => "Role 1",
                    "other" => "Other 1"
                ],
                [
                    "name" => "Organization 2",
                    "role" => "Role 2",
                    "other" => "Other 2"
                ]
            ],
            'industries' => [
                [
                    "name" => "Industires 1",
                    "role" => "Role 1",
                ],
                [
                    "name" => "Industires 2",
                    "role" => "Role 2",
                ]
            ],
            'project_leaders' => [
                [
                    "name" => "Project Leader 1",
                    "organization" => "LKM",
                    "man_month" => 12
                ],
                [
                    "name" => "Project Leader 2",
                    "organization" => "LKM",
                    "man_month" => 12
                ]
            ],
            'researchers' => [
                [
                    "name" => "Researcher 1",
                    "organization" => "LKM",
                    "man_month" => 12
                ],
                [
                    "name" => "Researcher 2",
                    "organization" => "LKM",
                    "man_month" => 12
                ]
            ],
            'staffs' => [
                [
                    "name" => "Staff 1",
                    "organization" => "LKM",
                    "man_month" => 12
                ],
                [
                    "name" => "Staff 2",
                    "organization" => "LKM",
                    "man_month" => 12
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

        $response = $this->put(route('panel.trf.sub-form.form7.update', ["form7" => $proposal]), [
            'project_leader_type' => $proposal->project_leader_type,

            'organizations' => [
                [
                    "name" => "Organization 1",
                    "role" => "Role 1",
                    "other" => "Other 1"
                ],
                [
                    "name" => "Organization 2",
                    "role" => "Role 2",
                    "other" => "Other 2"
                ]
            ],
            'industries' => [
                [
                    "name" => "Industires 1",
                    "role" => "Role 1",
                ],
                [
                    "name" => "Industires 2",
                    "role" => "Role 2",
                ]
            ],
            'project_leaders' => [
                [
                    "name" => "Project Leader 1",
                    "organization" => "LKM",
                    "man_month" => 12
                ],
                [
                    "name" => "Project Leader 2",
                    "organization" => "LKM",
                    "man_month" => 12
                ]
            ],
            'researchers' => [
                [
                    "name" => "Researcher 1",
                    "organization" => "LKM",
                    "man_month" => 12
                ],
                [
                    "name" => "Researcher 2",
                    "organization" => "LKM",
                    "man_month" => 12
                ]
            ],
            'staffs' => [
                [
                    "name" => "Staff 1",
                    "organization" => "LKM",
                    "man_month" => 12
                ],
                [
                    "name" => "Staff 2",
                    "organization" => "LKM",
                    "man_month" => 12
                ]
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
