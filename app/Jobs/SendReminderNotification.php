<?php

namespace App\Jobs;

use App\Mail\NotificationMail;
use App\Models\ReminderSendLog;
use App\Models\Template;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReminderNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $reminderSendLogId;
    private $template;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        int $reminderSendLogId
    ) {
        $this->reminderSendLogId = $reminderSendLogId;

        $this->template = Template::query()
            ->where("category", "reminder")
            ->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->template) {
            $alert = "template not found, please run 'php artisan db:seed --class=TemplateSeeder'";
            Log::alert($alert);
            return $alert;
        }

        $sendLog = ReminderSendLog::find($this->reminderSendLogId);

        $sendLog = $sendLog->load("reminder.category", "ref");

        if (!$sendLog) {
            Log::info("Send Log Not Found : ({$this->reminderSendLogId})");
            return "Send Log Not Found";
        }

        $proposal = $sendLog->ref;

        $name = optional($proposal->researcher)->name;

        $email = config("app.env") == "production" && !config("mail.force_test")
            ? $sendLog->recipient
            : config("mail.test_email");

        $title = optional($sendLog->reminder->category)->description;

        $dataOptions = [
            "reportCategory" => $title,
            "referenceTitle" => $proposal->project_title,
            "notes" => $sendLog->reminder->notes
        ];

        try {
            Mail::to($email)
                ->send(new NotificationMail(
                    $name,
                    "[Reminder] " . $title,
                    $this->template,
                    $dataOptions
                ));

            $sendLog->update([
                "sent_at" => now(),
                "status" => ReminderSendLog::STATUS_SUCCESS
            ]);

            return "Email Sent";
        } catch (Exception $e) {
            $sendLog->update([
                "status" => ReminderSendLog::STATUS_FAIL
            ]);
            Log::info("Email Fail : ({$this->reminderSendLogId})");
        }
    }
}
