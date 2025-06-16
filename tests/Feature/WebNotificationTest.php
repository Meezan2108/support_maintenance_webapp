<?php

namespace Tests\Feature;

use App\Actions\Documentation\CreateDocumentationNotification;
use App\Actions\Notification\GetCountUnreadNotification;
use App\Models\Documentation;
use App\Models\User;
use Tests\TestCase;

class WebNotificationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetNotificationPage()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('panel.notification.index'));

        $response->assertStatus(200);
    }

    public function testReadAllNotification()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $user->assignRole([User::ROLE_RESEARCHER]);

        $Documentation = Documentation::factory()->create();

        (new CreateDocumentationNotification)->execute($Documentation);

        $count = (new GetCountUnreadNotification)->execute($user);
        $this->assertTrue($count > 0);

        $response = $this->post(route("panel.notification.read-all"));

        $count = (new GetCountUnreadNotification)->execute($user);
        $this->assertTrue($count == 0);
    }

    public function testReadNotification()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $user->assignRole([User::ROLE_RESEARCHER]);

        $Documentation = Documentation::factory()->create();

        $notification = (new CreateDocumentationNotification)->execute($Documentation);

        $response = $this->put(route("panel.notification.update", ["notification" => $notification->id]));

        $response->assertStatus(200);

        $logExist = $notification->log()->where("user_id", $user->id)->exists();

        $this->assertTrue($logExist);
    }

    public function testNotificationNavBar()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $user->assignRole([User::ROLE_RESEARCHER]);

        $response = $this->get(route("resources.notification.index"));

        $response->assertStatus(200);
    }

    public function testCountNotification()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $user->assignRole([User::ROLE_RESEARCHER]);

        $response = $this->get(route("resources.notification.count"));

        $response->assertStatus(200);
    }
}
