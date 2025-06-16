<?php

namespace App\Jobs;

use App\Mail\NotificationMail;
use App\Models\Notifable;
use App\Models\RefDivision;
use App\Models\Template;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class SendEmailNotificationProposal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $title;
    private $targetType;
    private $targetId;
    private $templateCategory;
    private $dataOptions;

    private $division;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        string $title,
        int $notificationId,
        array $options = []
    ) {
        $this->title = $title;

        $notification = Notifable::with("reference")
            ->find($notificationId);

        $this->targetType = $notification->target_model_type;
        $this->targetId = $notification->target_model_id;
        $this->templateCategory = $notification->category;

        if (
            $this->targetType == Notifable::TARGET_TYPE_GROUP
            && $this->targetId == User::ROLE_DIVISION_DIRECTOR
        ) {
            $this->division = $notification->division;
        }

        $this->dataOptions = $notification->data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $template = Template::query()
            ->where("category", $this->templateCategory)
            ->where("status", 1)
            ->first();

        if (!$template) {
            Log::info("Template Not Found : ({$this->templateCategory})");
            return "Template Not Found";
        }

        if ($this->targetType == Notifable::TARGET_TYPE_USER) {
            $user = User::find($this->targetId);

            if (!$user) {
                Log::info("User Not Found : (id:{$this->targetId})");
                return "User Not Found";
            }

            $email = config("app.env") == "production"
                ? $user->email
                : config("mail.test_email");

            Mail::to($email)
                ->send(new NotificationMail(
                    $user->name,
                    $this->title,
                    $template,
                    $this->dataOptions
                ));

            return "Email Sent";
        }

        if ($this->targetType == Notifable::TARGET_TYPE_GROUP) {
            $users = User::query()
                ->when($this->division, function ($query) {
                    return $query->where("ref_division_id", $this->division->id);
                })
                ->whereHas('roles', function ($query) {
                    return $query->where("roles.id", $this->targetId);
                })->get();

            if ($users->count() == 0) {
                Log::info("No Users on Group : (id:{$this->targetId})");
                return "No Users on Group";
            }

            foreach ($users as $user) {
                $email = config("app.env") == "production"
                    ? $user->email
                    : config("mail.test_email");

                echo $email . " - " . $this->targetId . "\n";
                Mail::to($email)
                    ->send(new NotificationMail(
                        $user->name,
                        $this->title,
                        $template,
                        $this->dataOptions
                    ));
            }

            return "Email Group Sent";
        }
    }
}
