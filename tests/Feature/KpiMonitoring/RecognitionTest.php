<?php

namespace Tests\Feature\KpiMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\Recognition;
use App\Models\RefOutputType;
use App\Models\RefPatent;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class RecognitionTest extends TestCase
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

        $response = $this->get(route('panel.recognition.index'));

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

        $response = $this->get(route('panel.recognition.create'));

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

        $file = UploadedFile::fake()->image('avatar.jpg')->size(100);

        $response = $this->post(route('panel.recognition.store'), [
            'date' => '2023-08-02',
            'recognition' => 'Recognition',
            'type' => 1,
            'project' => 'Project',
            'new_files' => [$file],

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
        $recognition = Recognition::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $recognition->kpiAchievement()->create([
            "title" => $recognition->recognition,
            "user_id" => $user->id,
            "category_id" => Recognition::CATEGORY_ID,
            "date" => $recognition->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->get(route('panel.recognition.show', ["recognition" => $recognition->id]));

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
        $recognition = Recognition::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $recognition->kpiAchievement()->create([
            "title" => $recognition->recognition,
            "user_id" => $user->id,
            "category_id" => Recognition::CATEGORY_ID,
            "date" => $recognition->date,
            "approval_status" => Approvement::STATUS_AMEND
        ]);

        $response = $this->get(route('panel.recognition.edit', ["recognition" => $recognition->id]));

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
        $recognition = Recognition::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $recognition->kpiAchievement()->create([
            "title" => $recognition->recognition,
            "user_id" => $user->id,
            "category_id" => Recognition::CATEGORY_ID,
            "date" => $recognition->date,
            "approval_status" => Approvement::STATUS_AMEND
        ]);

        $file = UploadedFile::fake()->image('avatar.jpg')->size(100);

        $response = $this->put(route('panel.recognition.update', ["recognition" => $recognition->id]), [
            'date' => '2023-08-02',
            'recognition' => 'Recognition',
            'type' => 1,
            'project' => 'Project',
            'new_files' => [$file],

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
        $recognition = Recognition::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $recognition->kpiAchievement()->create([
            "title" => $recognition->recognition,
            "user_id" => $user->id,
            "category_id" => Recognition::CATEGORY_ID,
            "date" => $recognition->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->delete(route('panel.recognition.delete', ["recognition" => $recognition->id]));

        $response->assertStatus(302);
        $this->assertSoftDeleted(Recognition::class, ['id' => $recognition->id]);
    }

    public function testApprovementPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $recognition = Recognition::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $recognition->kpiAchievement()->create([
            "title" => $recognition->recognition,
            "user_id" => $user->id,
            "category_id" => Recognition::CATEGORY_ID,
            "date" => $recognition->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->get(route('panel.recognition.approvement', ["recognition" => $recognition->id]));

        $response->assertStatus(200);
    }

    public function testApprovementSubmit()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $recognition = Recognition::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $recognition->kpiAchievement()->create([
            "title" => $recognition->recognition,
            "user_id" => $user->id,
            "category_id" => Recognition::CATEGORY_ID,
            "date" => $recognition->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->put(route('panel.recognition.approvement', ["recognition" => $recognition->id]), [
            'approval_status' => Approvement::STATUS_APPROVED
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
