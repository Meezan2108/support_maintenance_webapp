<?php

namespace Tests\Feature\ProjectMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Actions\Test\ProjectMonitoring\CreateResearchProgressTest;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\RefReportType;
use App\Models\ReportQuarterly;
use App\Models\ReportResearchProgress;
use App\Models\User;
use Tests\TestCase;

class ResearchProgressNoFundTest extends TestCase
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

        $response = $this->get(route('panel.research-progress-no-fund.index'));

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

        $response = $this->get(route('panel.research-progress-no-fund.create'));

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

        $response = $this->post(route('panel.research-progress-no-fund.store'), [
            'year' => 2023,
            'proposal_id' => $proposal->id,
            'ref_report_type_id' => RefReportType::first()->id,
            'focus_area' => 'Focus Area',
            'issue' => 'Issue',
            'strategy' => 'Strategy',
            'program' => 'Program',
            'date' => '2023-08-31',

            'background' => 'Background',
            'result' => 'Result',
            'summary' => 'Summary',

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
        $report = (new CreateResearchProgressTest)->execute($proposal);

        $response = $this->get(route('panel.research-progress-no-fund.show', ["report" => $report->id]));

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
        $report = (new CreateResearchProgressTest)->execute($proposal);
        $report->approval_status = Approvement::STATUS_AMEND;
        $report->save();

        $response = $this->get(route('panel.research-progress-no-fund.edit', ["report" => $report->id]));

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
        $report = (new CreateResearchProgressTest)->execute($proposal);
        $report->approval_status = Approvement::STATUS_AMEND;
        $report->save();

        $milestone = $proposal->milestones;

        $response = $this->put(route('panel.research-progress-no-fund.update', ["report" => $report->id]), [
            'year' => 2023,
            'proposal_id' => $proposal->id,
            'ref_report_type_id' => RefReportType::first()->id,
            'focus_area' => 'Focus Area',
            'issue' => 'Issue',
            'strategy' => 'Strategy',
            'program' => 'Program',
            'date' => '2023-08-31',

            'background' => 'Background',
            'result' => 'Result',
            'summary' => 'Summary',

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
        $report = (new CreateResearchProgressTest)->execute($proposal);
        $report->approval_status = Approvement::STATUS_SUBMITED;
        $report->save();

        $response = $this->delete(route('panel.research-progress-no-fund.delete', ["report" => $report->id]));

        $response->assertStatus(302);
        $this->assertSoftDeleted(ReportResearchProgress::class, ['id' => $report->id]);
    }

    public function testCommentPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);
        $report = (new CreateResearchProgressTest)->execute($proposal);
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

        $response = $this->get(route('panel.research-progress-no-fund.comment', ["report" => $report->id]));

        $response->assertStatus(200);
    }

    public function testCommentSubmit()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user, Proposal::TYPE_EXTERNAL_FUND);

        $report = (new CreateResearchProgressTest)->execute($proposal);
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

        $response = $this->put(route('panel.research-progress-no-fund.comment', ["report" => $report->id]), [
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
        })->first();

        $userReviewer = $userReviewer ?? User::factory()->create();

        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->put(route('panel.research-progress-no-fund.comment', ["report" => $report->id]), [
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
        $report = (new CreateResearchProgressTest)->execute($proposal);

        $response = $this->get(route('panel.research-progress-no-fund.download', ["report" => $report->id]));

        $response->assertStatus(200);
    }
}
