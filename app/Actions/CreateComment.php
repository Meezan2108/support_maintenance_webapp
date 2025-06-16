<?php

namespace App\Actions;

use App\Models\Approvement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CreateComment
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return Approvement
     */
    public function execute(Model $model, User $user, array $comments, int $roleId)
    {
        $approvement = $model->approvement()
            ->where("user_id", $user->id)
            ->where("role_id", $roleId)
            ->first();

        if ($approvement) {
            $approvement->update([
                "date" => Carbon::now(),
                "comments" => $comments,
            ]);
        } else {
            $approvement = $model->approvement()->create([
                "user_id" => $user->id,
                "role_id" => $roleId,
                "status" => 0,
                "date" => Carbon::now(),
                "comments" => $comments,
                "version" => 1
            ]);
        }

        return $approvement;
    }
}
