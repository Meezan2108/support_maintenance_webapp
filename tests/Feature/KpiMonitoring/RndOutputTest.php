<?php

namespace Tests\Feature\KpiMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Models\Approvement;
use App\Models\OutputRnd;
use App\Models\Proposal;
use App\Models\RefOutputStatus;
use App\Models\RefOutputType;
use App\Models\User;
use Tests\TestCase;

class RndOutputTest extends TestCase
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

        $response = $this->get(route('panel.rnd-output.index'));

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

        $response = $this->get(route('panel.rnd-output.create'));

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

        $response = $this->post(route('panel.rnd-output.store'), [
            'date_output' => '2023-07-03',
            'output' => 'Output Title',

            'type' => RefOutputType::first()->id,
            'status' => RefOutputStatus::first()->id,
            "source_project" => 'Source Project Test',

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
        $outputRnd = OutputRnd::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $outputRnd->kpiAchievement()->create([
            "title" => $outputRnd->output,
            "user_id" => $user->id,
            "category_id" => OutputRnd::CATEGORY_ID,
            "date" => $outputRnd->date_output,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->get(route('panel.rnd-output.show', ["outputrnd" => $outputRnd->id]));

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
        $outputRnd = OutputRnd::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $outputRnd->kpiAchievement()->create([
            "title" => $outputRnd->output,
            "user_id" => $user->id,
            "category_id" => OutputRnd::CATEGORY_ID,
            "date" => $outputRnd->date_output,
            "approval_status" => Approvement::STATUS_AMEND
        ]);

        $response = $this->get(route('panel.rnd-output.edit', ["outputrnd" => $outputRnd->id]));

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
        $outputRnd = OutputRnd::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $outputRnd->kpiAchievement()->create([
            "title" => $outputRnd->output,
            "user_id" => $user->id,
            "category_id" => OutputRnd::CATEGORY_ID,
            "date" => $outputRnd->date_output,
            "approval_status" => Approvement::STATUS_AMEND
        ]);

        $response = $this->put(route('panel.rnd-output.update', ["outputrnd" => $outputRnd->id]), [
            'date_output' => '2023-07-03',
            'output' => 'Output Title',

            'type' => RefOutputType::first()->id,
            'status' => RefOutputStatus::first()->id,
            "source_project" => 'Source Project Test',

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
        $outputRnd = OutputRnd::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $outputRnd->kpiAchievement()->create([
            "title" => $outputRnd->output,
            "user_id" => $user->id,
            "category_id" => OutputRnd::CATEGORY_ID,
            "date" => $outputRnd->date_output,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->delete(route('panel.rnd-output.delete', ["outputrnd" => $outputRnd->id]));

        $response->assertStatus(302);
        $this->assertSoftDeleted(outputRnd::class, ['id' => $outputRnd->id]);
    }

    public function testApprovementPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $outputRnd = OutputRnd::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $outputRnd->kpiAchievement()->create([
            "title" => $outputRnd->output,
            "user_id" => $user->id,
            "category_id" => OutputRnd::CATEGORY_ID,
            "date" => $outputRnd->date_output,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->get(route('panel.rnd-output.approvement', ["outputrnd" => $outputRnd->id]));

        $response->assertStatus(200);
    }

    public function testApprovementSubmit()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $outputRnd = OutputRnd::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $outputRnd->kpiAchievement()->create([
            "title" => $outputRnd->output,
            "user_id" => $user->id,
            "category_id" => OutputRnd::CATEGORY_ID,
            "date" => $outputRnd->date_output,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->put(route('panel.rnd-output.approvement', ["outputrnd" => $outputRnd->id]), [
            'approval_status' => Approvement::STATUS_APPROVED
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
