<?php

namespace App\Actions\Notification;

use App\Models\Approvement;
use App\Models\Notifable;
use App\Models\NotifableLog;
use App\Models\User;

class MarkAllReadNotification
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return Approvement
     */
    public function execute(User $user)
    {
        $notifable = new Notifable();
        $log = new NotifableLog();

        $notifs = Notifable::query()
            ->leftJoin($log->getTable(), function ($join) use ($notifable, $log, $user) {
                $join->on($notifable->qualifyColumn("id"), "=", $log->qualifyColumn("notifable_id"))
                    ->where("user_id", $user->id)
                    ->where($log->qualifyColumn("status"), 1);
            })
            ->where(function ($query) use ($user) {
                return $query->where(function ($query) use ($user) {
                    return $query->where("target_model_type", "group")
                        ->whereIn("target_model_id", $user->roles->pluck("id")->toArray());
                })->orWhere(function ($query) use ($user) {
                    return $query->where("target_model_type", "user")
                        ->where("target_model_id", $user->id);
                });
            })
            ->whereNull($log->qualifyColumn("status"))
            ->groupBy($notifable->qualifyColumn("id"))
            ->select($notifable->qualifyColumn("id"))
            ->get();

        foreach ($notifs as $notification) {
            $notification->log()->create([
                "user_id" => $user->id,
                "status" => 1
            ]);
        }

        (new ClearNotificationCache)->execute($user);
    }
}
