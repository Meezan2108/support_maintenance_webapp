<?php

namespace Tests\Feature\ProjectMonitoring;

use App\Actions\Test\ManagementFund\CreateProposalTest;
use App\Actions\Test\ProjectMonitoring\CreateEopTest;
use App\Models\Approvement;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EopForm8Test extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStoreForm()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = (new CreateProposalTest)->execute($user);
        $report = (new CreateEopTest)->execute($proposal);

        $file = UploadedFile::fake()->image('avatar.jpg')->size(100);

        $response = $this->post(route('panel.end-of-project.sub-form.form8.store'), [
            'new_files' => [$file],
            'is_submited' => true
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }

    public function testUpdateForm()
    {
        /**
         * @var \App\Models\User \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        $user->assignRole([User::ROLE_RESEARCHER]);
        $this->actingAs($user);

        $proposal = (new CreateProposalTest)->execute($user);
        $report = (new CreateEopTest)->execute($proposal);
        $report->approval_status = Approvement::STATUS_AMEND;
        $report->save();

        $file = UploadedFile::fake()->image('avatar.jpg')->size(100);

        $response = $this->put(route('panel.end-of-project.sub-form.form8.update', ['form8' => $report->id]), [
            'new_files' => [$file],
            'is_submited' => true
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
    }
}
