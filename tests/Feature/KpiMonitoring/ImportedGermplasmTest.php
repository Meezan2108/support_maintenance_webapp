<?php

namespace Tests\Feature\KpiMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Models\Approvement;
use App\Models\ImportedGermplasm;
use App\Models\Proposal;
use App\Models\RefOutputType;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class ImportedGermplasmTest extends TestCase
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

        $response = $this->get(route('panel.imported-germplasm.index'));

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

        $response = $this->get(route('panel.imported-germplasm.create'));

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

        $response = $this->post(route('panel.imported-germplasm.store'), [
            'year' => 2023,
            'quarter' => 1,
            'no_germplasm' => 30,

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
        $germplasm = ImportedGermplasm::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $germplasm->kpiAchievement()->create([
            "title" => Carbon::parse($germplasm->date)->format("Y-m-d"),
            "user_id" => $user->id,
            "category_id" => ImportedGermplasm::CATEGORY_ID,
            "date" => $germplasm->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->get(route('panel.imported-germplasm.show', ["germplasm" => $germplasm->id]));

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
        $germplasm = ImportedGermplasm::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $germplasm->kpiAchievement()->create([
            "title" => Carbon::parse($germplasm->date)->format("Y-m-d"),
            "user_id" => $user->id,
            "category_id" => ImportedGermplasm::CATEGORY_ID,
            "date" => $germplasm->date,
            "approval_status" => Approvement::STATUS_AMEND
        ]);

        $response = $this->get(route('panel.imported-germplasm.edit', ["germplasm" => $germplasm->id]));

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
        $germplasm = ImportedGermplasm::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $germplasm->kpiAchievement()->create([
            "title" => Carbon::parse($germplasm->date)->format("Y-m-d"),
            "user_id" => $user->id,
            "category_id" => ImportedGermplasm::CATEGORY_ID,
            "date" => $germplasm->date,
            "approval_status" => Approvement::STATUS_AMEND
        ]);

        $response = $this->put(route('panel.imported-germplasm.update', ["germplasm" => $germplasm->id]), [
            'year' => 2023,
            'quarter' => 1,
            'no_germplasm' => 30,

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
        $germplasm = ImportedGermplasm::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $germplasm->kpiAchievement()->create([
            "title" => Carbon::parse($germplasm->date)->format("Y-m-d"),
            "user_id" => $user->id,
            "category_id" => ImportedGermplasm::CATEGORY_ID,
            "date" => $germplasm->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $response = $this->delete(route('panel.imported-germplasm.delete', ["germplasm" => $germplasm->id]));

        $response->assertStatus(302);
        $this->assertSoftDeleted(ImportedGermplasm::class, ['id' => $germplasm->id]);
    }

    public function testApprovementPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $germplasm = ImportedGermplasm::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $germplasm->kpiAchievement()->create([
            "title" => Carbon::parse($germplasm->date)->format("Y-m-d"),
            "user_id" => $user->id,
            "category_id" => ImportedGermplasm::CATEGORY_ID,
            "date" => $germplasm->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->get(route('panel.imported-germplasm.approvement', ["germplasm" => $germplasm->id]));

        $response->assertStatus(200);
    }

    public function testApprovementSubmit()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $germplasm = ImportedGermplasm::factory()->create([
            "user_id" => $user->id,
            "proposal_id" => $proposal->id
        ]);

        $germplasm->kpiAchievement()->create([
            "title" => Carbon::parse($germplasm->date)->format("Y-m-d"),
            "user_id" => $user->id,
            "category_id" => ImportedGermplasm::CATEGORY_ID,
            "date" => $germplasm->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->put(route('panel.imported-germplasm.approvement', ["germplasm" => $germplasm->id]), [
            'approval_status' => Approvement::STATUS_APPROVED
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
