<?php

namespace App\Jobs;

use App\Models\Proposal;
use App\Models\Reminder;
use App\Models\ReminderSendLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateReminderSendLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $reminderId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        int $reminderId
    ) {
        $this->reminderId = $reminderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $reminder = Reminder::find($this->reminderId);

        if (!$reminder) {
            Log::info("Reminder Not Found : ({$this->reminderId})");
            return "Reminder Not Found";
        }

        $proposals = Proposal::query()
            ->where("project_status", Proposal::STATUS_PRJ_ON_GOING)
            ->get();

        foreach ($proposals as $proposal) {

            if ($this->checkIsAlreadySend($reminder, $proposal)) {
                echo "Skip: id {$reminder->id}\n";
                continue;
            }

            $email = $proposal->researcher->email;

            $sendLog = $proposal->reminderSendLog()->create([
                "reminder_id" => $reminder->id,
                "recipient" => $email,
                "status" => ReminderSendLog::STATUS_PENDING,
                "scheduled_at" => now()
            ]);

            SendReminderNotification::dispatch($sendLog->id);
        }
    }

    public function checkIsAlreadySend($reminder, $proposal, ?Carbon $date = null)
    {
        $date = $date ?? now();
        return $proposal->reminderSendLog()
            ->where("reminder_id", $reminder->id)
            ->where('scheduled_at', ">=", $date->format("Y-m-d") . ' 00:00:00')
            ->where('scheduled_at', "<=", $date->format("Y-m-d") . ' 23:59:59')
            ->first();
    }
}
