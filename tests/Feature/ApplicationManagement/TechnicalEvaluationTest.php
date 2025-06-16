<?php

namespace Tests\Feature\ApplicationManagement;

use App\Actions\ManagementFund\GenerateApplicationId;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ProposalResearcher;
use App\Models\RefDivision;
use App\Models\RefEvaluationQuestion;
use App\Models\RefTypeOfFunding;
use App\Models\User;
use Tests\TestCase;

class TechnicalEvaluationTest extends TestCase
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
        $user->assignRole([User::ROLE_DIVISION_DIRECTOR]);
        $this->actingAs($user);

        $response = $this->get(route('panel.technical-evaluation.index'));

        $response->assertStatus(200);
    }

    public function testShowPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $division = RefDivision::where('is_active', 1)
            ->first();

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'proposal_type' => Proposal::TYPE_EXTERNAL_FUND,
            'ref_type_of_funding_id' => RefTypeOfFunding::first()->id,
            'approval_status' => Approvement::STATUS_SUBMITED
        ]);

        $researcher = ProposalResearcher::factory()->create([
            'proposal_id' => $proposal->id,
            'name' => $user->name,
            'email' => $user->email,
            'ref_division_id' => $division->id
        ]);

        $proposal = (new GenerateApplicationId)->execute($proposal);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userAuth = User::factory()->create([
            'ref_division_id' => $division->id
        ]);
        $userAuth->assignRole([User::ROLE_DIVISION_DIRECTOR]);
        $this->actingAs($userAuth);

        $questions = RefEvaluationQuestion::all();

        $arrAnswer = [];

        foreach ($questions as $question) {
            $arrAnswer["q_" . $question->id] = $question->options[1];
        }

        $response = $this->put(route('panel.technical-evaluation.update', ["proposal" => $proposal->id]), [
            'comments' => 'Comment',
            'approval_status' => Approvement::STATUS_APPROVED,
            'answers' => $arrAnswer
        ]);

        $response = $this->get(route('panel.technical-evaluation.show', ["proposal" => $proposal->id]));

        $response->assertStatus(200);
    }

    public function testEditPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_DIVISION_DIRECTOR]);

        $division = RefDivision::where('is_active', 1)
            ->first();

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'proposal_type' => Proposal::TYPE_EXTERNAL_FUND,
            'ref_type_of_funding_id' => RefTypeOfFunding::first()->id,
            'approval_status' => Approvement::STATUS_SUBMITED
        ]);

        $researcher = ProposalResearcher::factory()->create([
            'proposal_id' => $proposal->id,
            'name' => $user->name,
            'email' => $user->email,
            'ref_division_id' => $division->id
        ]);

        $proposal = (new GenerateApplicationId)->execute($proposal);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userAuth = User::factory()->create([
            'ref_division_id' => $division->id
        ]);
        $userAuth->assignRole([User::ROLE_DIVISION_DIRECTOR]);
        $this->actingAs($userAuth);

        $response = $this->get(route('panel.technical-evaluation.edit', ["proposal" => $proposal->id]));

        $response->assertStatus(200);
    }

    public function testUpdateData()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_DIVISION_DIRECTOR]);

        $division = RefDivision::where('is_active', 1)
            ->first();

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'proposal_type' => Proposal::TYPE_EXTERNAL_FUND,
            'ref_type_of_funding_id' => RefTypeOfFunding::first()->id,
            'approval_status' => Approvement::STATUS_SUBMITED
        ]);

        $researcher = ProposalResearcher::factory()->create([
            'proposal_id' => $proposal->id,
            'name' => $user->name,
            'email' => $user->email,
            'ref_division_id' => $division->id
        ]);

        $proposal = (new GenerateApplicationId)->execute($proposal);

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

        $questions = RefEvaluationQuestion::all();

        $arrAnswer = [];

        foreach ($questions as $question) {
            $arrAnswer["q_" . $question->id] = $question->options[1];
        }

        $response = $this->put(route('panel.technical-evaluation.update', ["proposal" => $proposal]), [
            'comments' => 'Comment',
            'approval_status' => Approvement::STATUS_APPROVED,
            'answers' => $arrAnswer
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();

        $userReviewer = User::query()
            ->whereHas("roles", function ($query) {
                $query->where("roles.id", User::ROLE_RMC);
            })
            ->where("ref_division_id", $proposal->researcher->ref_division_id)
            ->first();

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = $userReviewer ?? User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $questions = RefEvaluationQuestion::all();

        $arrAnswer = [];

        foreach ($questions as $question) {
            $arrAnswer["q_" . $question->id] = $question->options[1];
        }

        $response = $this->put(route('panel.technical-evaluation.update', ["proposal" => $proposal]), [
            'comments' => 'Comment',
            'approval_status' => Approvement::STATUS_APPROVED,
            'answers' => $arrAnswer
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
