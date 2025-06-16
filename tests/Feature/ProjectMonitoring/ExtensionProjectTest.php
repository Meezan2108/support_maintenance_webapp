<?php

namespace Tests\Feature\ProjectMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Actions\Test\ProjectMonitoring\CreateExtensionProjectTest;
use App\Actions\Test\ProjectMonitoring\CreateTrfMarTest;
use App\Models\Approvement;
use App\Models\ExtensionProject;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Models\User;
use Tests\TestCase;

class ExtensionProjectTest extends TestCase
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

        $response = $this->get(route('panel.extension-project.index'));

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

        $response = $this->get(route('panel.extension-project.create'));

        $response->assertStatus(200);
    }

    public function testStoreForm()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);

        $milestone = $proposal->milestones;

        $response = $this->post(route('panel.extension-project.store'), [
            'proposal_id' => $proposal->id,
            'justification' => 'Justification',
            'new_fund' => 'New Fund',
            'duration' => 12,
            'date_end_extension' => '2024-12-31',
            'balance_to_date' => 100000,
            'milestones_extension' => $milestone->map(function ($item) {
                return [
                    'activities' => $item->activities,
                    'from' => $item->from->format("Y-m-d"),
                ];
            })->toArray(),
            'is_submited' => 1
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
        $application = (new CreateExtensionProjectTest)->execute($proposal);

        $response = $this->get(route('panel.extension-project.show', ["application" => $application->id]));

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
        $application = (new CreateExtensionProjectTest)->execute($proposal);
        $application->approval_status = Approvement::STATUS_AMEND;
        $application->save();

        $response = $this->get(route('panel.extension-project.edit', ["application" => $application->id]));

        $response->assertStatus(200);
    }

    public function testUpdateForm()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $application = (new CreateExtensionProjectTest)->execute($proposal);
        $application->approval_status = Approvement::STATUS_AMEND;
        $application->save();

        $milestone = $proposal->milestones;

        $response = $this->put(route('panel.extension-project.update', ["application" => $application->id]), [
            'proposal_id' => $proposal->id,
            'justification' => 'Justification',
            'new_fund' => 'New Fund',
            'duration' => 12,
            'date_end_extension' => '2024-12-31',
            'balance_to_date' => 100000,
            'milestones_extension' => $milestone->map(function ($item) {
                return [
                    'activities' => $item->activities,
                    'from' => $item->from->format("Y-m-d"),
                ];
            })->toArray(),
            'is_submited' => 1
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
        $application = (new CreateExtensionProjectTest)->execute($proposal);
        $application->approval_status = Approvement::STATUS_SUBMITED;
        $application->save();

        $response = $this->delete(route('panel.extension-project.delete', ["application" => $application->id]));

        $response->assertStatus(302);
        $this->assertSoftDeleted(ExtensionProject::class, ['id' => $application->id]);
    }

    public function testCommentPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $application = (new CreateExtensionProjectTest)->execute($proposal);
        $application->approval_status = Approvement::STATUS_SUBMITED;
        $application->save();

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create([
            "ref_division_id" => $proposal->researcher->ref_division_id
        ]);
        $userReviewer->assignRole([User::ROLE_DIVISION_DIRECTOR]);
        $this->actingAs($userReviewer);

        $response = $this->get(route('panel.extension-project.comment', ["application" => $application->id]));

        $response->assertStatus(200);
    }

    public function testCommentSubmit()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);

        $application = (new CreateExtensionProjectTest)->execute($proposal);
        $application->approval_status = Approvement::STATUS_SUBMITED;
        $application->save();

        $userReviewer = User::query()
            ->whereHas("roles", function ($query) {
                $query->where("roles.id", User::ROLE_DIVISION_DIRECTOR);
            })
            ->where("ref_division_id", $proposal->researcher->ref_division_id)
            ->first();

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = $userReviewer ?? User::factory()->create([
            "ref_division_id" => $proposal->researcher->ref_division_id
        ]);
        $userReviewer->assignRole([User::ROLE_DIVISION_DIRECTOR]);
        $this->actingAs($userReviewer);

        $response = $this->put(route('panel.extension-project.comment', ["application" => $application->id]), [
            'comment' => 'Comment',
            'is_submited' => 1,
            'status' => ReportQuarterly::STATUS_APPROVED
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::whereHas("roles", function ($query) {
            $query->where("roles.id", User::ROLE_RMC);
        })->first() ?? User::factory()->create();

        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->put(route('panel.extension-project.comment', ["application" => $application->id]), [
            'comment' => 'Comment',
            'is_submited' => 1,
            'status' => ReportQuarterly::STATUS_APPROVED
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }

    public function testDownloadPdf()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $application = (new CreateExtensionProjectTest)->execute($proposal);

        $response = $this->get(route('panel.extension-project.download', ["application" => $application->id]));

        $response->assertStatus(200);
    }
}
