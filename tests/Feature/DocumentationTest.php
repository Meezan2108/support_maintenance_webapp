<?php

namespace Tests\Feature;

use App\Models\Documentation;
use App\Models\RefOtherDocument;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DocumentationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_SUPERADMIN]);
        $this->actingAs($user);

        $response = $this->get(route('panel.documentation.index'));

        $response->assertStatus(200);
    }

    public function testIndexPageAsResearcher()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $response = $this->get(route('panel.documentation.index'));

        $response->assertStatus(200);
    }

    public function testCreateDocumentationPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_SUPERADMIN]);
        $this->actingAs($user);

        $response = $this->get(route("panel.documentation.create"));

        $response->assertStatus(200);
    }

    public function testCreateDocumentationPageAsResearcher()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $response = $this->get(route("panel.documentation.create"));

        $response->assertStatus(403);
    }

    public function testStoreDocumentation()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_SUPERADMIN]);
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('avatar.jpg')->size(100);

        $description = "Test Description " . now();
        $response = $this->post(route("panel.documentation.store"), [
            'submission_date' => date("Y-m-d"),
            'description' => $description,
            'category' => RefOtherDocument::first()->id,
            'new_files' => [$file],
            'is_submited' => true,
        ]);

        $response->assertRedirect(route("panel.documentation.index"));

        $documentation = Documentation::query()
            ->where("description", $description)
            ->first();

        $isFileExists = $documentation->fileable()->exists();

        $this->assertTrue($isFileExists);
    }

    public function testShowDocumentationPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_SUPERADMIN]);
        $this->actingAs($user);

        $documentation = Documentation::factory()->create();

        $response = $this->get(route("panel.documentation.show", ["documentation" => $documentation->id]));

        $response->assertStatus(200);
    }

    public function testShowDocumentationPageAsResearcher()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_SUPERADMIN]);
        $this->actingAs($user);

        $documentation = Documentation::factory()->create();

        $response = $this->get(route("panel.documentation.show", ["documentation" => $documentation->id]));

        $response->assertStatus(200);
    }

    public function testEditDocumentationPage()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_SUPERADMIN]);
        $this->actingAs($user);

        $documentation = Documentation::factory()->create();

        $response = $this->get(route("panel.documentation.edit", ["documentation" => $documentation->id]));

        $response->assertStatus(200);
    }

    public function testUpdateDocumentation()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_SUPERADMIN]);
        $this->actingAs($user);

        $documentation = Documentation::factory()->create();

        $file = UploadedFile::fake()->image('avatar.jpg')->size(100);

        $description = "Test Description " . now();
        $response = $this->put(route("panel.documentation.update", ["documentation" => $documentation]), [
            'submission_date' => date("Y-m-d"),
            'description' => $description,
            'category' => RefOtherDocument::first()->id,
            'new_files' => [$file],
            'old_files' => [],
            'is_submited' => true,
        ]);

        $response->assertRedirect(route("panel.documentation.index"));

        $documentation = Documentation::query()
            ->where("description", $description)
            ->first();

        $isFileExists = $documentation->fileable()->exists();

        $this->assertTrue($isFileExists);
    }

    public function testDeleteDocumentation()
    {
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_SUPERADMIN]);
        $this->actingAs($user);

        $documentation = Documentation::factory()->create();

        $response = $this->delete(route("panel.documentation.delete", ["documentation" => $documentation]));

        $response->assertRedirect(route("panel.documentation.index"));

        $this->assertSoftDeleted(Documentation::class, ['id' => $documentation->id]);
    }
}
