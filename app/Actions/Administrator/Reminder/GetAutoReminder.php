<?php

namespace App\Actions\Administrator\Reminder;

use App\Jobs\CreateReminderSendLog;
use App\Models\Reminder;
use Carbon\Carbon;

class GetAutoReminder
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Carbon $dateTime)
    {
        $reminders = Reminder::query()
            ->where("is_manual", 0)
            ->where("status", 1)
            ->get();

        $arrAvailableReminder = [];
        foreach ($reminders as $reminder) {
            // echo "Reminder Active ID: {$reminder->id}\n";

            if (!$this->isAvailable($reminder, $dateTime)) {
                continue;
            }

            echo "Reminder Proceed: {$dateTime->format('Y-m-d')}\n";
            echo "Reminder ID: {$reminder->id}\n";
            echo "\n - - - - \n\n";

            $arrAvailableReminder[] = $reminder;
        }

        return $arrAvailableReminder;
    }

    public function isAvailable(Reminder $reminder, $dateTime)
    {
        $monthNow = $dateTime->format('m');
        $dayNow = $dateTime->format('d');

        if ($reminder->repeat_type == Reminder::REPEAT_YEARLY) {
            return $monthNow == str_pad($reminder->options["month"], 2, '0', STR_PAD_LEFT)
                && $dayNow == str_pad($reminder->options["day"], 2, '0', STR_PAD_LEFT);
        }

        if ($reminder->repeat_type == Reminder::REPEAT_BIANUALLY) {
            $temp = (intval($monthNow) % 6) + 1;
            return $temp == $reminder->options["month"]
                && $dayNow == str_pad($reminder->options["day"], 2, '0', STR_PAD_LEFT);
        }

        if ($reminder->repeat_type == Reminder::REPEAT_TRIANUALLY) {
            $temp = (intval($monthNow) % 4) + 1;
            return $temp == $reminder->options["month"]
                && $dayNow == str_pad($reminder->options["day"], 2, '0', STR_PAD_LEFT);
        }

        if ($reminder->repeat_type == Reminder::REPEAT_QUARTERLY) {
            $temp = (intval($monthNow) % 3) + 1;
            return $temp == $reminder->options["month"]
                && $dayNow == str_pad($reminder->options["day"], 2, '0', STR_PAD_LEFT);
        }

        if ($reminder->repeat_type == Reminder::REPEAT_MONTHLY) {
            return $dayNow == str_pad($reminder->options["day"], 2, '0', STR_PAD_LEFT);
        }

        if ($reminder->repeat_type == Reminder::REPEAT_WEEKLY) {
            return ($dateTime->dayOfWeek) == (($reminder->options["day"]) % 7);
        }

        return false;
    }
}
