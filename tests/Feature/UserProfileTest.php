<?php

namespace Tests\Feature;

use App\Models\RefDivision;
use App\Models\RefPosition;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetProfile()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->get('/profile');

        $response->assertStatus(200);
    }

    public function testGetEditProfile()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->get(route("panel.profile.edit"));

        $response->assertStatus(200);
    }

    public function testUpdateProfile()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->put(route("panel.profile.update"), [
            'name' => 'Test Edit',
            'nric' => 'NRIC Edit',
            'salutation' => 'Sir.',
            'qualification' => 'S.Kom',
            'ref_division_id' => RefDivision::first()->id,
            'ref_position_id' => RefPosition::first()->id,
            // 'file_picture' => null,
            // 'old_picture' => null,
            'researcher_id' => 'TEST0001'
        ]);

        $response->assertRedirect(route("panel.profile"));
    }

    public function testUpdateCredentials()
    {
        $oldPassword = "abc12345";
        $newPassword = "abc12345_1";

        $user = User::factory()->create([
            "password" => Hash::make($oldPassword)
        ]);

        $this->actingAs($user);
        $response = $this->put(route("panel.profile.update-creds"), [
            "email" => $user->email,
            "password" => $newPassword,
            "password_confirmation" => $newPassword,
            "password_old" => $oldPassword
        ]);

        $user->fresh();

        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    public function testUpdateCredentialsFail()
    {
        $oldPassword = "abc12345";

        $user = User::factory()->create([
            "password" => Hash::make($oldPassword)
        ]);

        $this->actingAs($user);
        $response = $this->put(route("panel.profile.update-creds"), [
            "email" => 'notemaildata',
            "password" => 'wrong-password',
            "password_confirmation" => 'abc',
            "password_old" => 'wrong-password'
        ]);


        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password', // List the field names you expect to have errors
        ]);
    }
}
