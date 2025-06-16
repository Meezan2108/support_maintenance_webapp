<?php

namespace Tests\Feature\KpiMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Models\AnalyticalServiceLab;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\RefOutputType;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class AnalyticalServiceLabTest extends TestCase
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

        $response = $this->get(route('panel.analytical-service-lab.index'));

        $response->assertStatus(200);
    }

    public function testCreatePage()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $response = $this->get(route('panel.analytical-service-lab.create'));

        $response->assertStatus(200);
    }

    public function testSubmitStoreForm()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $response = $this->post(route('panel.analytical-service-lab.store'), [
            'year' => 2023,
            'quarter' => 1,
            'no_sample' => 200,
            'no_analysis' => 150,
            'no_analysis_protocol' => 75,

            'proposal_id' => null,
            "is_submited" => true
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
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
        $asl = AnalyticalServiceLab::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $asl->kpiAchievement()->create([
            "title" => Carbon::parse($asl->date)->format("Y-m-d"),
            "user_id" => $user->id,
            "category_id" => AnalyticalServiceLab::CATEGORY_ID,
            "date" => $asl->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->get(route('panel.analytical-service-lab.show', ["analytical" => $asl->id]));

        $response->assertStatus(200);
    }

    public function testEditPage()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $asl = AnalyticalServiceLab::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $asl->kpiAchievement()->create([
            "title" => Carbon::parse($asl->date)->format("Y-m-d"),
            "user_id" => $user->id,
            "category_id" => AnalyticalServiceLab::CATEGORY_ID,
            "date" => $asl->date,
            "approval_status" => Approvement::STATUS_AMEND
        ]);

        $response = $this->get(route('panel.analytical-service-lab.edit', ["analytical" => $asl->id]));

        $response->assertStatus(200);
    }

    public function testSubmitUpdateForm()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $asl = AnalyticalServiceLab::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $asl->kpiAchievement()->create([
            "title" => Carbon::parse($asl->date)->format("Y-m-d"),
            "user_id" => $user->id,
            "category_id" => AnalyticalServiceLab::CATEGORY_ID,
            "date" => $asl->date,
            "approval_status" => Approvement::STATUS_AMEND
        ]);

        $response = $this->put(route('panel.analytical-service-lab.update', ["analytical" => $asl->id]), [
            'year' => 2023,
            'quarter' => 1,
            'no_sample' => 200,
            'no_analysis' => 150,
            'no_analysis_protocol' => 75,

            'proposal_id' => null,
            "is_submited" => true
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }

    public function testDelete()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $asl = AnalyticalServiceLab::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $asl->kpiAchievement()->create([
            "title" => Carbon::parse($asl->date)->format("Y-m-d"),
            "user_id" => $user->id,
            "category_id" => AnalyticalServiceLab::CATEGORY_ID,
            "date" => $asl->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->delete(route('panel.analytical-service-lab.delete', ["analytical" => $asl->id]));

        $response->assertStatus(302);
        $this->assertSoftDeleted(AnalyticalServiceLab::class, ['id' => $asl->id]);
    }

    public function testApprovementPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $asl = AnalyticalServiceLab::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $asl->kpiAchievement()->create([
            "title" => Carbon::parse($asl->date)->format("Y-m-d"),
            "user_id" => $user->id,
            "category_id" => AnalyticalServiceLab::CATEGORY_ID,
            "date" => $asl->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->get(route('panel.analytical-service-lab.approvement', ["analytical" => $asl->id]));

        $response->assertStatus(200);
    }

    public function testApprovementSubmit()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $asl = AnalyticalServiceLab::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $asl->kpiAchievement()->create([
            "title" => Carbon::parse($asl->date)->format("Y-m-d"),
            "user_id" => $user->id,
            "category_id" => AnalyticalServiceLab::CATEGORY_ID,
            "date" => $asl->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->put(route('panel.analytical-service-lab.approvement', ["analytical" => $asl->id]), [
            'approval_status' => Approvement::STATUS_APPROVED
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
