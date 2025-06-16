<?php

namespace App\Actions\Notification;

use App\Models\Approvement;
use App\Models\Notifable;
use App\Models\User;

class ReadNotification
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return Approvement
     */
    public function execute(User $user, Notifable $notification)
    {
        $log = $notification->log()
            ->where("user_id", $user->id)
            ->first();

        if (!$log) {
            $notification->log()->create([
                "user_id" => $user->id,
                "status" => 1
            ]);
        } else {
            $log->update([
                "status" => 1
            ]);
        }

        (new ClearNotificationCache)->execute($user);
    }
}
