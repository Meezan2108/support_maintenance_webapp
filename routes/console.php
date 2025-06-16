<?php

use App\Actions\Administrator\Reminder\GetAutoReminder;
use App\Actions\ApplicationManagement\TechnicalEvaluation\UpdateProposalApprovement;
use App\Actions\UpdateApprovementStep;
use App\Jobs\CreateReminderSendLog;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('auto-reminder', function () {
    $dateTime = Carbon::now();
    Log::info("Run auto reminder:" . $dateTime->format("Y-m-d H:i:s") . "\n");

    $reminders = (new GetAutoReminder)->execute($dateTime);
    foreach ($reminders as $reminder) {
        $hour = $dateTime->format('H');
        $delay = intval($reminder->options["hour"] - $hour) . "\n";

        echo "Delayed: {$delay}\n";

        CreateReminderSendLog::dispatch($reminder->id)
            ->delay($dateTime->addHours($delay));
    }
})->purpose('Execute auto reminder');

Artisan::command('test-auto-reminder', function () {
    $firstDate = Carbon::parse("2024-01-01 01:00:00");

    for ($i = 0; $i <= 365; $i++) {
        $dateNow = $firstDate->addDays(1);
        (new GetAutoReminder)->execute($dateNow);
    }
})->purpose('Test auto reminder');

Artisan::command('replace-approval-by-rmc', function () {
    $proposals = Proposal::query()
        ->where("approval_status", Approvement::STATUS_APPROVED)
        ->where("is_by_rmc", 1)
        ->get();

    foreach ($proposals as $item) {
        /**
         * @var Proposal
         */
        $proposal = $item;

        $user = User::query()
            ->where("ref_division_id", $proposal->researcher->division->id)
            ->whereHas("roles", function ($query) {
                return $query->where("id", User::ROLE_DIVISION_DIRECTOR);
            })
            ->first();

        if (!$user) {
            echo "skip: " . $proposal->id . ' | ' . $proposal->project_title . "\n";
            continue;
        }

        DB::transaction(function () use ($proposal, $user) {

            $arrData["approval_status"] = Approvement::STATUS_APPROVED;

            $approvementStep = $proposal->approvementStep;
            $approvementStep->step = 0;
            $approvementStep->save();

            (new UpdateProposalApprovement)->execute($proposal, $user, $arrData, 0);
            (new UpdateApprovementStep)->execute($proposal->refresh(), $user);

            $user = User::query()
                ->whereHas("roles", function ($query) {
                    return $query->where("id", User::ROLE_RMC);
                })
                ->first();
            (new UpdateProposalApprovement)->execute($proposal->refresh(), $user, $arrData, 1);
            (new UpdateApprovementStep)->execute($proposal->refresh(), $user);

            $user = User::query()
                ->where("email", "kamil@koko.gov.my")
                ->first();
            (new UpdateProposalApprovement)->execute($proposal->refresh(), $user, $arrData, 2);
            (new UpdateApprovementStep)->execute($proposal->refresh(), $user);

            echo "done: " . $proposal->id . ' | ' . $proposal->project_title . "\n";
        });
    }
})->purpose('Replace Approval By RMC');
