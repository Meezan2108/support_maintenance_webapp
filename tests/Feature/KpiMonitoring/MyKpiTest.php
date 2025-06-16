<?php

namespace Tests\Feature\KpiMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\Recognition;
use App\Models\User;
use Tests\TestCase;

class MyKpiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexPage()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $response = $this->get(route('panel.my-kpi.index'));

        $response->assertStatus(200);
    }

    public function testShowPage()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $recognition = Recognition::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $kpiAchievement = $recognition->kpiAchievement()->create([
            "title" => $recognition->recognition,
            "user_id" => $user->id,
            "category_id" => Recognition::CATEGORY_ID,
            "date" => $recognition->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->get(route('panel.my-kpi.show', ["mykpi" => $kpiAchievement->id]));

        $response->assertStatus(200);
    }
}
