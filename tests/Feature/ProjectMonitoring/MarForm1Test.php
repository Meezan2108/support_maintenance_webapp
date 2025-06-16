<?php

namespace Tests\Feature\ProjectMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Actions\Test\ProjectMonitoring\CreateTrfMarTest;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ProposalMilestone;
use App\Models\User;
use Tests\TestCase;

class MarForm1Test extends TestCase
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

        $year = 2023;
        $quarter = 3;

        $milestones = ProposalMilestone::query()
            ->where("proposal_id", $proposal->id)
            ->filterByQuarter($year, $quarter)
            ->get();

        $response = $this->post(route('panel.monitoring-trf.mar.sub-form.form1.store'), [
            'proposal_id' => $proposal->id,
            'year_quarter' => '2023-1',
            'year' => 2023,
            'quarter' => 1,
            'milestones' => $milestones->map(function ($item) {
                return [
                    'id' => $item->id,
                    'is_achieved' => true,
                    'completion_date' => $item->from->format('Y-m')
                ];
            })->toArray(),

            'reason_not_achieved' =>  '',
            'corrective_action' =>  '',
            'revised_completion_date' =>  null,

            'request_extension' => 0,
            'new_completion_date' => null,
            'reason_for_extension' => '',
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

        $year = $report->year;
        $quarter = $report->quarter;

        $milestones = ProposalMilestone::query()
            ->where("proposal_id", $proposal->id)
            ->filterByQuarter($year, $quarter)
            ->get();

        $response = $this->put(route('panel.monitoring-trf.mar.sub-form.form1.update', ['form1' => $report->id]), [
            'proposal_id' => $proposal->id,
            'year_quarter' => $year . '-' . $quarter,
            'year' => $year,
            'quarter' => $quarter,
            'milestones' => $milestones->map(function ($item) {
                return [
                    'id' => $item->id,
                    'is_achieved' => true,
                    'completion_date' => $item->from->format('Y-m')
                ];
            })->toArray(),

            'reason_not_achieved' =>  '',
            'corrective_action' =>  '',
            'revised_completion_date' =>  null,

            'request_extension' => 0,
            'new_completion_date' => null,
            'reason_for_extension' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
