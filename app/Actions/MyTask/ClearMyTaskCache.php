<?php

namespace App\Actions\MyTask;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ClearMyTaskCache
{
    /**
     * Execute the action
     *
     * @param  User  $user
     * @param  bool  $forceNoCache
     * 
     * @return void
     */
    public function execute(int $userId)
    {
        Cache::flush("get_count_my_task_" . $userId);
    }
}
