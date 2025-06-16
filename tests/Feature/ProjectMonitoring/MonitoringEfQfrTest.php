<?php

namespace Tests\Feature\ProjectMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Actions\Test\ProjectMonitoring\CreateTrfQfrTest;
use App\Models\Approvement;
use App\Models\ReportQuarterly;
use App\Models\User;
use Tests\TestCase;

class MonitoringEfQfrTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreatePage()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $response = $this->get(route('panel.monitoring-ef.qfr.create'));

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

        $proposal = (new CreateProposalTest)->execute($user);
        $report = (new CreateTrfQfrTest)->execute($proposal);

        $response = $this->get(route('panel.monitoring-ef.qfr.show', ["qfr" => $report->id]));

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

        $proposal = (new CreateProposalTest)->execute($user);
        $report = (new CreateTrfQfrTest)->execute($proposal);
        $report->approval_status = Approvement::STATUS_AMEND;
        $report->save();

        $response = $this->get(route('panel.monitoring-ef.qfr.edit', ["qfr" => $report->id]));

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

        $proposal = (new CreateProposalTest)->execute($user);
        $report = (new CreateTrfQfrTest)->execute($proposal);
        $report->approval_status = Approvement::STATUS_SUBMITED;
        $report->save();

        $response = $this->delete(route('panel.monitoring-ef.qfr.delete', ["qfr" => $report->id]));

        $response->assertStatus(302);
        $this->assertSoftDeleted(ReportQuarterly::class, ['id' => $report->id]);
    }

    public function testCommentPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user);
        $report = (new CreateTrfQfrTest)->execute($proposal);
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

        $response = $this->get(route('panel.monitoring-ef.qfr.comment', ["qfr" => $report->id]));

        $response->assertStatus(200);
    }

    public function testCommentSubmit()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $proposal = (new CreateProposalTest)->execute($user);
        $report = (new CreateTrfQfrTest)->execute($proposal);
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

        $response = $this->put(route('panel.monitoring-ef.qfr.comment', ["qfr" => $report->id]), [
            'project_details' => 'project details',
            'financial_progress' => 'financial progress',
            'budget_variations' => 'budget variation',
            'proposed_action' => 'proposed action',
            'last' => true,
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

        $response = $this->put(route('panel.monitoring-ef.qfr.comment', ["qfr" => $report->id]), [
            'project_details' => 'project details',
            'financial_progress' => 'financial progress',
            'budget_variations' => 'budget variation',
            'proposed_action' => 'proposed action',
            'last' => true,
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

        $proposal = (new CreateProposalTest)->execute($user);
        $report = (new CreateTrfQfrTest)->execute($proposal);

        $response = $this->get(route('panel.monitoring-ef.qfr.download', ["qfr" => $report->id]));

        $response->assertStatus(200);
    }
}
