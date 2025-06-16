<?php

namespace Tests\Feature;

use App\Actions\Documentation\CreateDocumentationNotification;
use App\Actions\KpiMonitoring\CreateAnalyticalServiceLabSubmitTask;
use App\Actions\Notification\GetCountUnreadNotification;
use App\Models\AnalyticalServiceLab;
use App\Models\Approvement;
use App\Models\Documentation;
use App\Models\Taskable;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class TaskTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetMyTaskPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);

        $this->actingAs($user);

        $response = $this->get(route('panel.my-task.index'));

        $response->assertStatus(200);
    }

    public function testDeleteMyTask()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER, User::ROLE_RMC]);
        $this->actingAs($user);

        $asl = AnalyticalServiceLab::factory()->create();

        $asl->kpiAchievement()->create([
            "title" => Carbon::parse($asl->date)->format("Y-m-d"),
            "user_id" => $user->id,
            "category_id" => AnalyticalServiceLab::CATEGORY_ID,
            "date" => $asl->date,
            "approval_status" => Approvement::STATUS_SUBMITED
        ]);

        $task = (new CreateAnalyticalServiceLabSubmitTask)->execute($asl, $user);

        $response = $this->delete(route('panel.my-task.delete', ["task" => $task->id]));

        $this->assertSoftDeleted(Taskable::class, ['id' => $task->id]);
    }

    public function testCountTask()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $user->assignRole([User::ROLE_RESEARCHER]);

        $response = $this->get(route("resources.task.count"));

        $response->assertStatus(200);
    }
}
