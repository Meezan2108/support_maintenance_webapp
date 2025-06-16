<?php

namespace App\Actions\Notification;

use App\Models\Notifable;
use App\Models\NotifableLog;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class GetCountUnreadNotification
{
    /**
     * Execute the action
     *
     * @param  User $user
     * @param  bool $forceNoCache
     * 
     * @return int
     */
    public function execute(User $user, bool $forceNoCache = false)
    {
        if (!$user) return 0;

        $cacheKey = "get_count_notif_navbar" . $user->id . "-" . ($user->ref_division_id ?? 0);

        if (Cache::has($cacheKey) && !$forceNoCache) {
            $count = Cache::get($cacheKey);
            if ($count) return $count;
        }

        $notifable = new Notifable();
        $log = new NotifableLog();
        $count = Notifable::query()
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
            ->distinct($notifable->qualifyColumn("id"))
            ->count();

        Cache::put($cacheKey, $count, 5 * 60);

        return $count;
    }
}
