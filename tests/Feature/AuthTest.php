<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetLoginPage()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function testUserLogin()
    {
        $user = User::factory()->create([
            "password" => Hash::make("abc12345")
        ]);

        $response = $this->post(route('login.submit'), [
            "email" => $user->email,
            "password" => "abc12345"
        ]);

        $response->assertRedirect(route('panel.dashboard'));
    }

    public function testUserLoginfail()
    {
        $response = $this->get(route('panel.dashboard'));

        $response->assertRedirect(route('login'));
    }
}
