<?php

namespace App\Actions\MyTask;

use App\Actions\MyTask\ClearMyTaskCache;
use App\Models\Approvement;
use App\Models\Taskable;
use App\Models\User;

class ClearMyTask
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return Approvement
     */
    public function execute(User $user)
    {
        $taskable = new Taskable();

        $notifs = Taskable::query()
            ->where(function ($query) use ($user, $taskable) {
                return $query->where(function ($query) use ($user, $taskable) {
                    return $query->where("code_type", "group")
                        ->when(
                            $user->hasRole("Division Director")
                                && !$user->hasRole(["LKM Director", "R&D Coordinator", "RMC"]),
                            function ($query) use ($user, $taskable) {
                                return $query->where(
                                    $taskable->qualifyColumn("ref_division_id"),
                                    optional($user->division)->id
                                );
                            }
                        )
                        ->whereIn("model_id", $user->roles->pluck("id")->toArray());
                })->orWhere(function ($query) use ($user) {
                    return $query->where("code_type", "user")
                        ->where("model_id", $user->id);
                });
            })
            ->delete();

        foreach ($notifs as $notification) {
            $notification->log()->create([
                "user_id" => $user->id,
                "status" => 1
            ]);
        }

        (new ClearMyTaskCache)->execute($user->id);
    }
}
