<?php

namespace Tests\Feature\ProjectMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Actions\Test\ProjectMonitoring\CreateTrfMarTest;
use App\Actions\Test\ProjectMonitoring\CreateTrfQfrTest;
use App\Models\Approvement;
use App\Models\RefProjectCostSeries;
use App\Models\User;
use Tests\TestCase;

class QfrForm4Test extends TestCase
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
        $report = (new CreateTrfQfrTest)->execute($proposal);

        $response = $this->post(route('panel.monitoring-trf.qfr.sub-form.form4.store'), [
            'proposed_action' => 'Reasons',
            'is_submited' => 1
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
        $report = (new CreateTrfQfrTest)->execute($proposal);
        $report->approval_status = Approvement::STATUS_AMEND;
        $report->save();

        $response = $this->put(route('panel.monitoring-trf.qfr.sub-form.form4.update', ['form4' => $report->id]), [
            'proposed_action' => 'Reasons',
            'is_submited' => 1
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
