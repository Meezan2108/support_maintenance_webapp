<?php

namespace Tests\Feature\ApplicationManagement;

use App\Actions\ManagementFund\GenerateApplicationId;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ProposalResearcher;
use App\Models\RefDivision;
use App\Models\RefTypeOfFunding;
use App\Models\User;
use Tests\TestCase;

class ListOfRejectedTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RMC]);
        $this->actingAs($user);

        $response = $this->get(route('panel.list-of-rejected.index'));

        $response->assertStatus(200);
    }
}
