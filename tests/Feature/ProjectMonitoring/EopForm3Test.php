<?php

namespace Tests\Feature\ProjectMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Actions\Test\ProjectMonitoring\CreateEopTest;
use App\Models\Approvement;
use App\Models\User;
use Tests\TestCase;

class EopForm3Test extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStoreForm()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = (new CreateProposalTest)->execute($user);
        $report = (new CreateEopTest)->execute($proposal);

        $response = $this->post(route('panel.end-of-project.sub-form.form3.store'), [
            'objectives_achieved' =>  [
                [
                    "description" => 'Objectives Achieved'
                ]
            ],
            'objectives_not_achieved' =>  [
                [
                    "description" => 'Objectives Not Achieved'
                ]
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }

    public function testUpdateForm()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = (new CreateProposalTest)->execute($user);
        $report = (new CreateEopTest)->execute($proposal);
        $report->approval_status = Approvement::STATUS_AMEND;
        $report->save();

        $response = $this->put(route('panel.end-of-project.sub-form.form3.update', ['form3' => $report->id]), [
            'objectives_achieved' =>  [
                [
                    "description" => 'Objectives Achieved'
                ]
            ],
            'objectives_not_achieved' =>  [
                [
                    "description" => 'Objectives Not Achieved'
                ]
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
