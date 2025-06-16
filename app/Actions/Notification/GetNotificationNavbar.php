<?php

namespace App\Actions\Notification;

use App\Http\Resources\Notification\NotificationTableResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GetNotificationNavbar
{
    /**
     * Execute the action
     *
     * @param  User $user
     * @param  array $data
     * @return array
     */
    public function execute(User $user, bool $forceNoCache = false)
    {
        if (!$user) return [];

        $cacheKey = "get_notif_navbar" . $user->id . "-" . ($user->ref_division_id ?? 0);
        if (Cache::has($cacheKey) && !$forceNoCache) {
            $notifCollections = Cache::get($cacheKey);
            if ($notifCollections) return $notifCollections;
        }

        $notifCollections = NotificationTableResource::collection((new GetNotification)->execute($user, ['per_page' => 5]));
        $notifCollections = $notifCollections->toArray((new Request()));

        Cache::put($cacheKey, $notifCollections, 5 * 60);

        return $notifCollections;
    }
}
