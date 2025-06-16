<?php

namespace Tests\Feature\ProjectMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Actions\Test\ProjectMonitoring\CreateTrfMarTest;
use App\Actions\Test\ProjectMonitoring\CreateTrfQfrTest;
use App\Models\Approvement;
use App\Models\RefProjectCostSeries;
use App\Models\User;
use Tests\TestCase;

class QfrForm2Test extends TestCase
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

        $projectCostSeries = RefProjectCostSeries::query()
            ->whereIn("vseries_code", ["V21000", "V26000", "V28000", "V29000"])
            ->get();

        $actualExpenditure = $projectCostSeries->map(function ($item) {
            return [
                'ref_project_cost_series_id' => $item->id,
                'total_approved' => 100,
                'total_recieved' => 100,
                'total_expenditure' => 100
            ];
        });

        $response = $this->post(route('panel.monitoring-trf.qfr.sub-form.form2.store'), [
            'proposal_id' => $proposal->id,
            'year_quarter' => '2023-3',
            'year' => 2023,
            'quarter' => 3,
            'total_recieved' => $actualExpenditure->sum('total_recieved'),
            'total_expenditure' => $actualExpenditure->sum('total_expenditure'),
            'is_inline_plan' => 1,

            'actual_project_expenditure' => $actualExpenditure->toArray()
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

        $projectCostSeries = RefProjectCostSeries::query()
            ->whereIn("vseries_code", ["V21000", "V26000", "V28000", "V29000"])
            ->get();

        $actualExpenditure = $projectCostSeries->map(function ($item) {
            return [
                'ref_project_cost_series_id' => $item->id,
                'total_approved' => 100,
                'total_recieved' => 100,
                'total_expenditure' => 100
            ];
        });

        $response = $this->put(route('panel.monitoring-trf.qfr.sub-form.form2.update', ['form2' => $report->id]), [
            'proposal_id' => $proposal->id,
            'year_quarter' => '2023-3',
            'year' => 2023,
            'quarter' => 3,
            'total_recieved' => $actualExpenditure->sum('total_recieved'),
            'total_expenditure' => $actualExpenditure->sum('total_expenditure'),
            'is_inline_plan' => 1,

            'actual_project_expenditure' => $actualExpenditure->toArray()
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
