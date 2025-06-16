<?php

namespace Tests\Feature\KpiMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Models\Approvement;
use App\Models\Commercialization;
use App\Models\Proposal;
use App\Models\RefOutputType;
use App\Models\RefPatent;
use App\Models\User;
use Tests\TestCase;

class CommercializationTest extends TestCase
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

        $response = $this->get(route('panel.commercialization.index'));

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

        $response = $this->get(route('panel.commercialization.create'));

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

        $response = $this->post(route('panel.commercialization.store'), [
            'date' => '2023-08-02',
            'name' => 'Commercialization Name',

            "taker" => 'Taker Company',
            'category' => RefOutputType::first()->id,

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
        $commercialization = Commercialization::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $commercialization->kpiAchievement()->create([
            "title" => $commercialization->name,
            "user_id" => $user->id,
            "category_id" => Commercialization::CATEGORY_ID,
            "date" => $commercialization->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->get(route('panel.commercialization.show', ["commercialization" => $commercialization->id]));

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
        $commercialization = Commercialization::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $commercialization->kpiAchievement()->create([
            "title" => $commercialization->name,
            "user_id" => $user->id,
            "category_id" => Commercialization::CATEGORY_ID,
            "date" => $commercialization->date,
            "approval_status" => Approvement::STATUS_AMEND
        ]);

        $response = $this->get(route('panel.commercialization.edit', ["commercialization" => $commercialization->id]));

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
        $commercialization = Commercialization::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $commercialization->kpiAchievement()->create([
            "title" => $commercialization->name,
            "user_id" => $user->id,
            "category_id" => Commercialization::CATEGORY_ID,
            "date" => $commercialization->date,
            "approval_status" => Approvement::STATUS_AMEND
        ]);

        $response = $this->put(route('panel.commercialization.update', ["commercialization" => $commercialization->id]), [
            'date' => '2023-08-02',
            'name' => 'Commercialization Name',

            "taker" => 'Taker Company',
            'category' => RefOutputType::first()->id,

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
        $commercialization = Commercialization::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $commercialization->kpiAchievement()->create([
            "title" => $commercialization->name,
            "user_id" => $user->id,
            "category_id" => Commercialization::CATEGORY_ID,
            "date" => $commercialization->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->delete(route('panel.commercialization.delete', ["commercialization" => $commercialization->id]));

        $response->assertStatus(302);
        $this->assertSoftDeleted(Commercialization::class, ['id' => $commercialization->id]);
    }

    public function testApprovementPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $commercialization = Commercialization::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $commercialization->kpiAchievement()->create([
            "title" => $commercialization->name,
            "user_id" => $user->id,
            "category_id" => Commercialization::CATEGORY_ID,
            "date" => $commercialization->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->get(route('panel.commercialization.approvement', ["commercialization" => $commercialization->id]));

        $response->assertStatus(200);
    }

    public function testApprovementSubmit()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $commercialization = Commercialization::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $commercialization->kpiAchievement()->create([
            "title" => $commercialization->name,
            "user_id" => $user->id,
            "category_id" => Commercialization::CATEGORY_ID,
            "date" => $commercialization->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->put(route('panel.commercialization.approvement', ["commercialization" => $commercialization->id]), [
            'approval_status' => Approvement::STATUS_APPROVED
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
