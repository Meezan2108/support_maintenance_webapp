<?php

namespace Tests\Feature\ProjectMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Actions\Test\ProjectMonitoring\CreateEopTest;
use App\Actions\Test\ProjectMonitoring\CreateResearchProgressTest;
use App\Models\Approvement;
use App\Models\ExtensionProject;
use App\Models\Proposal;
use App\Models\ReportEndProject;
use App\Models\ReportQuarterly;
use App\Models\User;
use Tests\TestCase;

class EopTest extends TestCase
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

        $response = $this->get(route('panel.end-of-project.index'));

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

        $response = $this->get(route('panel.end-of-project.create'));

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
        $report = (new CreateEopTest)->execute($proposal);

        $response = $this->get(route('panel.end-of-project.show', ["report" => $report->id]));

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
        $report = (new CreateEopTest)->execute($proposal);
        $report->approval_status = Approvement::STATUS_AMEND;
        $report->save();

        $response = $this->get(route('panel.end-of-project.edit', ["report" => $report->id]));

        $response->assertStatus(200);
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
        $report = (new CreateEopTest)->execute($proposal);
        $report->approval_status = Approvement::STATUS_SUBMITED;
        $report->save();

        $response = $this->delete(route('panel.end-of-project.delete', ["report" => $report->id]));

        $response->assertStatus(302);
        $this->assertSoftDeleted(ReportEndProject::class, ['id' => $report->id]);
    }

    public function testCommentPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $report = (new CreateEopTest)->execute($proposal);
        $report->approval_status = Approvement::STATUS_SUBMITED;
        $report->save();

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create([
            "ref_division_id" => $proposal->researcher->ref_division_id
        ]);
        $userReviewer->assignRole([User::ROLE_DIVISION_DIRECTOR]);
        $this->actingAs($userReviewer);

        $response = $this->get(route('panel.end-of-project.comment', ["report" => $report->id]));

        $response->assertStatus(200);
    }

    public function testCommentSubmit()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);

        $report = (new CreateEopTest)->execute($proposal);
        $report->approval_status = Approvement::STATUS_SUBMITED;
        $report->save();

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

        $response = $this->put(route('panel.end-of-project.comment', ["report" => $report->id]), [
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

        $response = $this->put(route('panel.end-of-project.comment', ["report" => $report->id]), [
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
        $report = (new CreateEopTest)->execute($proposal);

        $response = $this->get(route('panel.end-of-project.download', ["report" => $report->id]));

        $response->assertStatus(200);
    }
}
