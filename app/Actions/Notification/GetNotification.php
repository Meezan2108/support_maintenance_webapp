<?php

namespace App\Actions\Notification;

use App\Models\Notifable;
use App\Models\User;

class GetNotification
{
    /**
     * Execute the action
     *
     * @param  User $user
     * @param  array $data
     * @return array
     */
    public function execute(User $user, array $filters = [])
    {
        // todo: add cache here

        $notifications = Notifable::query()
            ->with(["log" => function ($query) use ($user) {
                return $query->where("user_id", $user->id);
            }, "template"])
            ->where(function ($query) use ($user) {
                return $query->where(function ($query) use ($user) {
                    return $query->where("target_model_type", "group")
                        ->when(
                            $user->hasRole("Division Director")
                                && !$user->hasRole(["LKM Director", "R&D Coordinator", "RMC"]),
                            function ($query) use ($user) {
                                return $query->where("ref_division_id", optional($user->division)->id);
                            }
                        )
                        ->whereIn("target_model_id", $user->roles->pluck("id")->toArray());
                })->orWhere(function ($query) use ($user) {
                    return $query->where("target_model_type", "user")
                        ->where("target_model_id", $user->id);
                });
            })
            ->orderBy("updated_at", "DESC")
            ->paginate($filters['per_page'] ?? 10)
            ->withQueryString();

        return $notifications;
    }
}
