<?php

namespace App\Actions\MyTask;

use App\Models\Taskable;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class GetCountMyTask
{
    /**
     * Execute the action
     *
     * @param  User  $user
     * @param  bool  $forceNoCache
     *
     * @return int
     */
    public function execute(User $user, bool $forceNoCache = false)
    {
        if (!$user) return 0;

        $cacheKey = "get_count_my_task_" . $user->id;

        if (Cache::has($cacheKey) && !$forceNoCache) {
            return Cache::get($cacheKey);
        }

        $taskable = new Taskable();
        $count = Taskable::query()
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
            ->count();

        Cache::put($cacheKey, $count, 3 * 60 * 60);

        return $count;
    }
}
