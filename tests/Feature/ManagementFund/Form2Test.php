<?php

namespace Tests\Feature\ManagementFund;

use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\RefDivision;
use App\Models\RefForCategory;
use App\Models\RefPosition;
use App\Models\RefResearchCluster;
use App\Models\RefResearchType;
use App\Models\RefSeoCategory;
use App\Models\User;
use Tests\TestCase;

class Form2Test extends TestCase
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

        $response = $this->post(route('panel.trf.sub-form.form2.store', [
            'project_leader_type' => Proposal::TYPE_LEADER_INTERNAL,
            'proposal_type' => Proposal::TYPE_TRF,
            'objectives' => [
                [
                    "description" => 'Objectives 1'
                ],
                [
                    "description" => 'Objectives 2'
                ],
            ],
            'ref_research_type_id' => RefResearchType::first()->id,
            'ref_research_cluster_id' => RefResearchCluster::first()->id,

            'ref_seo_category_id' => RefSeoCategory::first()->id,
            'ref_seo_group_id' => RefSeoCategory::first()->group()->first()->id,
            'ref_seo_area_id' => RefSeoCategory::first()->group()->first()->area()->first()->id,

            'for_primary' => [
                'ref_for_category_id' => RefForCategory::first()->id,
                'ref_for_group_id' => RefForCategory::first()->group()->first()->id,
                'ref_for_area_id' => RefForCategory::first()->group()->first()->area()->first()->id,
            ],

            'for_secondary' => [
                'ref_for_category_id' => RefForCategory::latest()->first()->id,
                'ref_for_group_id' => RefForCategory::latest()->first()->group()->first()->id,
                'ref_for_area_id' => RefForCategory::latest()->first()->group()->first()->area()->first()->id,
            ]
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

        $response = $this->put(route('panel.trf.sub-form.form2.update', ["form2" => $proposal]), [
            'project_leader_type' => $proposal->project_leader_type,
            'objectives' => [
                [
                    "description" => 'Objectives 1'
                ],
                [
                    "description" => 'Objectives 2'
                ],
            ],
            'ref_research_type_id' => RefResearchType::first()->id,
            'ref_research_cluster_id' => RefResearchCluster::first()->id,

            'ref_seo_category_id' => RefSeoCategory::first()->id,
            'ref_seo_group_id' => RefSeoCategory::first()->group()->first()->id,
            'ref_seo_area_id' => RefSeoCategory::first()->group()->first()->area()->first()->id,

            'for_primary' => [
                'ref_for_category_id' => RefForCategory::first()->id,
                'ref_for_group_id' => RefForCategory::first()->group()->first()->id,
                'ref_for_area_id' => RefForCategory::first()->group()->first()->area()->first()->id,
            ],

            'for_secondary' => [
                'ref_for_category_id' => RefForCategory::latest()->first()->id,
                'ref_for_group_id' => RefForCategory::latest()->first()->group()->first()->id,
                'ref_for_area_id' => RefForCategory::latest()->first()->group()->first()->area()->first()->id,
            ]
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
