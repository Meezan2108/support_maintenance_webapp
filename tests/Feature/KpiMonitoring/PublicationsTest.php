<?php

namespace Tests\Feature\KpiMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\Publication;
use App\Models\RefPubType;
use App\Models\User;
use Tests\TestCase;

class PublicationsTest extends TestCase
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

        $response = $this->get(route('panel.publications.index'));

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

        $response = $this->get(route('panel.publications.create'));

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

        $response = $this->post(route('panel.publications.store'), [
            'date_published' => '2023-08-02',
            'title' => 'Title Publication',

            'ref_pub_type_id' => RefPubType::first()->id,
            'publisher' => 'Publisher',
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
        $publication = Publication::factory()->create([
            "user_id" => $user->id,
            "created_by" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $publication->kpiAchievement()->create([
            "title" => $publication->title,
            "user_id" => $user->id,
            "category_id" => Publication::CATEGORY_ID,
            "date" => $publication->date_published,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->get(route('panel.publications.show', ["publication" => $publication->id]));

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
        $publication = Publication::factory()->create([
            "user_id" => $user->id,
            "created_by" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $publication->kpiAchievement()->create([
            "title" => $publication->title,
            "user_id" => $user->id,
            "category_id" => Publication::CATEGORY_ID,
            "date" => $publication->date_published,
            "approval_status" => Approvement::STATUS_AMEND
        ]);

        $response = $this->get(route('panel.publications.edit', ["publication" => $publication->id]));

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
        $publication = Publication::factory()->create([
            "user_id" => $user->id,
            "created_by" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $publication->kpiAchievement()->create([
            "title" => $publication->title,
            "user_id" => $user->id,
            "category_id" => Publication::CATEGORY_ID,
            "date" => $publication->date_published,
            "approval_status" => Approvement::STATUS_AMEND
        ]);

        $response = $this->put(route('panel.publications.update', ["publication" => $publication->id]), [
            'date_published' => '2023-08-02',
            'title' => 'Title Publication',

            'ref_pub_type_id' => RefPubType::first()->id,
            'publisher' => 'Publisher',
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
        $publication = Publication::factory()->create([
            "user_id" => $user->id,
            "created_by" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $publication->kpiAchievement()->create([
            "title" => $publication->title,
            "user_id" => $user->id,
            "category_id" => Publication::CATEGORY_ID,
            "date" => $publication->date_published,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->delete(route('panel.publications.delete', ["publication" => $publication->id]));

        $response->assertStatus(302);
        $this->assertSoftDeleted(Publication::class, ['id' => $publication->id]);
    }

    public function testApprovementPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $publication = Publication::factory()->create([
            "user_id" => $user->id,
            "created_by" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $publication->kpiAchievement()->create([
            "title" => $publication->title,
            "user_id" => $user->id,
            "category_id" => Publication::CATEGORY_ID,
            "date" => $publication->date_published,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->get(route('panel.publications.approvement', ["publication" => $publication->id]));

        $response->assertStatus(200);
    }

    public function testApprovementSubmit()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $publication = Publication::factory()->create([
            "user_id" => $user->id,
            "created_by" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $publication->kpiAchievement()->create([
            "title" => $publication->title,
            "user_id" => $user->id,
            "category_id" => Publication::CATEGORY_ID,
            "date" => $publication->date_published,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->put(route('panel.publications.approvement', ["publication" => $publication->id]), [
            'approval_status' => Approvement::STATUS_APPROVED
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
