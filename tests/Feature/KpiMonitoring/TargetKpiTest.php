<?php

namespace Tests\Feature\KpiMonitoring;

use App\Models\Approvement;
use App\Models\RefTargetKpiCategory;
use App\Models\RefTargetKpiPeriod;
use App\Models\TargetKpi;
use App\Models\User;
use Database\Seeders\MenuSeeder;
use Database\Seeders\RefTargetKpiCategorySeeder;
use Database\Seeders\RefTargetKpiPeriodSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TargetKpiTest extends TestCase
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
        $user->assignRole([User::ROLE_RMC]);
        $this->actingAs($user);

        $response = $this->get(route('panel.target-kpi.index'));

        $response->assertStatus(200);
    }

    public function testCreatePage()
    {
        $this->seed(MenuSeeder::class);
        $this->seed(RoleSeeder::class);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RMC]);
        $this->actingAs($user);

        $response = $this->get(route('panel.target-kpi.create'));

        $response->assertStatus(200);
    }

    public function testSubmitStoreForm()
    {
        $this->seed(MenuSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(RefTargetKpiCategorySeeder::class);
        $this->seed(RefTargetKpiPeriodSeeder::class);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RMC]);
        $this->actingAs($user);


        $refTargetCategory = RefTargetKpiCategory::first();
        $refPeriod = RefTargetKpiPeriod::where('type', $refTargetCategory->type)
            ->first();

        $response = $this->post(route('panel.target-kpi.store'), [
            'user_id' => $user->id,
            'year' => 2022,
            'category_id' => $refTargetCategory->id,
            'sub_category_id' => null,
            'period_id' => $refPeriod->id,
            'target' => 30,
            "is_submited" => true
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
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
        $user->assignRole([User::ROLE_RMC]);
        $this->actingAs($user);

        $target = TargetKpi::factory()->create([
            "user_id" => $user->id
        ]);

        $response = $this->get(route('panel.target-kpi.show', ["target" => $target->id]));

        $response->assertStatus(200);
    }

    public function testEditPage()
    {
        $this->seed(MenuSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(RefTargetKpiCategorySeeder::class);
        $this->seed(RefTargetKpiPeriodSeeder::class);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RMC]);
        $this->actingAs($user);

        $target = TargetKpi::factory()->create([
            "user_id" => $user->id
        ]);
        $target->approval_status = Approvement::STATUS_AMEND;
        $target->save();

        $response = $this->get(route('panel.target-kpi.edit', ["target" => $target->id]));

        $response->assertStatus(200);
    }

    public function testSubmitUpdateForm()
    {
        $this->seed(MenuSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(RefTargetKpiCategorySeeder::class);
        $this->seed(RefTargetKpiPeriodSeeder::class);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RMC]);
        $this->actingAs($user);

        $target = TargetKpi::factory()->create([
            "user_id" => $user->id
        ]);
        $target->approval_status = Approvement::STATUS_AMEND;
        $target->save();

        $refTargetCategory = RefTargetKpiCategory::first();
        $refPeriod = RefTargetKpiPeriod::where('type', $refTargetCategory->type)
            ->first();

        $response = $this->put(route('panel.target-kpi.update', ["target" => $target->id]), [
            'user_id' => $user->id,
            'year' => 2023,
            'category_id' => $refTargetCategory->id,
            'sub_category_id' => null,
            'period_id' => $refPeriod->id,
            'target' => 30,
            "is_submited" => true
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }

    public function testDelete()
    {

        $this->seed(MenuSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(RefTargetKpiCategorySeeder::class);
        $this->seed(RefTargetKpiPeriodSeeder::class);

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RMC]);
        $this->actingAs($user);

        $target = TargetKpi::factory()->create([
            "user_id" => $user->id
        ]);
        $target->approval_status = Approvement::STATUS_SUBMITED;
        $target->save();

        $response = $this->delete(route('panel.target-kpi.delete', ["target" => $target->id]));

        $response->assertStatus(302);
        $this->assertSoftDeleted(TargetKpi::class, ['id' => $target->id]);
    }

    public function testApprovementPage()
    {

        $this->seed(MenuSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(RefTargetKpiCategorySeeder::class);
        $this->seed(RefTargetKpiPeriodSeeder::class);

        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RMC]);

        $target = TargetKpi::factory()->create([
            "user_id" => $user->id
        ]);
        $target->approval_status = Approvement::STATUS_SUBMITED;
        $target->save();

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->get(route('panel.target-kpi.approvement', ["target" => $target->id]));

        $response->assertStatus(403);
    }

    public function testApprovementSubmit()
    {

        $this->seed(MenuSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(RefTargetKpiCategorySeeder::class);
        $this->seed(RefTargetKpiPeriodSeeder::class);

        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RMC]);

        $target = TargetKpi::factory()->create([
            "user_id" => $user->id
        ]);
        $target->approval_status = Approvement::STATUS_SUBMITED;
        $target->save();

        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $userReviewer = User::factory()->create();
        $userReviewer->assignRole([User::ROLE_RMC]);
        $this->actingAs($userReviewer);

        $response = $this->put(route('panel.target-kpi.approvement', ["target" => $target->id]), [
            'approval_status' => Approvement::STATUS_APPROVED
        ]);

        $response->assertStatus(403);
        $response->assertSessionDoesntHaveErrors();
    }
}
