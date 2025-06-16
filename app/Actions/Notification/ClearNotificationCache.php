<?php

namespace App\Actions\Notification;

use App\Models\Approvement;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ClearNotificationCache
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return Approvement
     */
    public function execute(User $user)
    {
        Cache::forget('get_count_notif_navbar' . $user->id . "-" . ($user->ref_division_id ?? 0));
        Cache::forget('get_notif_navbar' . $user->id . "-" . ($user->ref_division_id ?? 0));
    }
}
