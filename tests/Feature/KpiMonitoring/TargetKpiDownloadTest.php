<?php

namespace Tests\Feature\KpiMonitoring;

use App\Models\Approvement;
use App\Models\TargetKpi;
use App\Models\User;
use Database\Seeders\MenuSeeder;
use Database\Seeders\RefTargetKpiCategorySeeder;
use Database\Seeders\RefTargetKpiPeriodSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TargetKpiDownloadTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexPage()
    {
        $this->seed(MenuSeeder::class);
        $this->seed(RoleSeeder::class);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $response = $this->get(route('panel.target-kpi.download.index'));

        $response->assertStatus(403);
    }

    public function testShowPage()
    {
        $this->seed(MenuSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(RefTargetKpiCategorySeeder::class);
        $this->seed(RefTargetKpiPeriodSeeder::class);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $target = TargetKpi::factory()->create([
            "user_id" => $user->id
        ]);

        $target->approval_status = Approvement::STATUS_APPROVED;
        $target->save();

        $response = $this->get(route('panel.target-kpi.download.show', [
            "year" => $target->year,
            "researcher_id" => $user->id
        ]));

        $response->assertStatus(403);
    }
}
