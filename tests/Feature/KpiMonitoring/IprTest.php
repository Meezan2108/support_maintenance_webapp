<?php

namespace Tests\Feature\KpiMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Models\Approvement;
use App\Models\IPR;
use App\Models\Proposal;
use App\Models\RefPatent;
use App\Models\User;
use Tests\TestCase;

class IprTest extends TestCase
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

        $response = $this->get(route('panel.ipr.index'));

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

        $response = $this->get(route('panel.ipr.create'));

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

        $response = $this->post(route('panel.ipr.store'), [
            'date' => '2023-08-02',
            'output' => 'Output IPR',
            'ref_patent_id' => RefPatent::first()->id,

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
        $ipr = IPR::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $ipr->kpiAchievement()->create([
            "title" => $ipr->output,
            "user_id" => $user->id,
            "category_id" => IPR::CATEGORY_ID,
            "date" => $ipr->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->get(route('panel.ipr.show', ["ipr" => $ipr->id]));

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
        $ipr = IPR::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $ipr->kpiAchievement()->create([
            "title" => $ipr->output,
            "user_id" => $user->id,
            "category_id" => IPR::CATEGORY_ID,
            "date" => $ipr->date,
            "approval_status" => Approvement::STATUS_AMEND
        ]);

        $response = $this->get(route('panel.ipr.edit', ["ipr" => $ipr->id]));

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
        $ipr = IPR::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $ipr->kpiAchievement()->create([
            "title" => $ipr->output,
            "user_id" => $user->id,
            "category_id" => IPR::CATEGORY_ID,
            "date" => $ipr->date,
            "approval_status" => Approvement::STATUS_AMEND
        ]);

        $response = $this->put(route('panel.ipr.update', ["ipr" => $ipr->id]), [
            'date' => '2023-08-02',
            'output' => 'Output IPR',
            'ref_patent_id' => RefPatent::first()->id,

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
        $ipr = IPR::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $ipr->kpiAchievement()->create([
            "title" => $ipr->output,
            "user_id" => $user->id,
            "category_id" => IPR::CATEGORY_ID,
            "date" => $ipr->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->delete(route('panel.ipr.delete', ["ipr" => $ipr->id]));

        $response->assertStatus(302);
        $this->assertSoftDeleted(IPR::class, ['id' => $ipr->id]);
    }

    public function testApprovementPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $ipr = IPR::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $ipr->kpiAchievement()->create([
            "title" => $ipr->output,
            "user_id" => $user->id,
            "category_id" => IPR::CATEGORY_ID,
            "date" => $ipr->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->get(route('panel.ipr.approvement', ["ipr" => $ipr->id]));

        $response->assertStatus(200);
    }

    public function testApprovementSubmit()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $ipr = IPR::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $ipr->kpiAchievement()->create([
            "title" => $ipr->output,
            "user_id" => $user->id,
            "category_id" => IPR::CATEGORY_ID,
            "date" => $ipr->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->put(route('panel.ipr.approvement', ["ipr" => $ipr->id]), [
            'approval_status' => Approvement::STATUS_APPROVED
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
