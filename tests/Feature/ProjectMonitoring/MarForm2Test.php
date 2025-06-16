<?php

namespace Tests\Feature\ProjectMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Actions\Test\ProjectMonitoring\CreateTrfMarTest;
use App\Models\Approvement;
use App\Models\Commercialization;
use App\Models\Proposal;
use App\Models\ProposalMilestone;
use App\Models\User;
use Tests\TestCase;

class MarForm2Test extends TestCase
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

        $proposal = (new CreateProposalTest)->execute($user);
        (new CreateTrfMarTest)->execute($proposal);

        $response = $this->post(route('panel.monitoring-trf.mar.sub-form.form2.store'), [
            'ipr' =>  [
                [
                    "output" => "test Output 1",
                    "date" => "2022-02-12"
                ]
            ],
            'publications' => [],
            'expertise_development' => [],
            'prototype' => [],
            'commercialization' => [],
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }

    public function testUpdateForm()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = (new CreateProposalTest)->execute($user);
        $report = (new CreateTrfMarTest)->execute($proposal);
        $report->approval_status = Approvement::STATUS_AMEND;
        $report->save();

        $response = $this->put(route('panel.monitoring-trf.mar.sub-form.form2.update', ['form2' => $report->id]), [
            'ipr' =>  [
                [
                    "output" => "test Output 1",
                    "date" => "2022-02-12"
                ],
                [
                    "output" => "test Output 2",
                    "date" => "2022-04-30"
                ]
            ],
            'publications' => [
                [
                    "title" => "Publication Title",
                    "publisher" => "Publisher",
                    "ref_pub_type_id" => 1,
                    "date" => "2023-03-22"
                ]
            ],
            'expertise_development' => [
                [
                    "output" => "Output",
                    "date" => "2023-03-22"
                ]
            ],
            'prototype' => [
                [
                    "output" => "Output",
                    "date" => "2023-03-22"
                ]
            ],
            'commercialization' => [
                [
                    "category" => 1,
                    "name" => "Product Commercialization",
                    "taker" => "Taker",
                    "date" => "2023-03-22"
                ]
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
