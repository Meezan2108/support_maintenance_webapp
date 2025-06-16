<?php

namespace Tests\Feature\ProjectMonitoring;

use App\Models\User;
use Tests\TestCase;

class MonitoringTrfTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $response = $this->get(route('panel.monitoring-trf.index'));

        $response->assertStatus(200);
    }
}
