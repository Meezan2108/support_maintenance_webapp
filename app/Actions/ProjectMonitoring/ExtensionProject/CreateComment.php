<?php

namespace App\Actions\ProjectMonitoring\ExtensionProject;

use App\Models\ExtensionProject;
use App\Models\ReportQuarterly;
use App\Models\User;
use Carbon\Carbon;

class CreateComment
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ExtensionProject $application, User $user, array $arrData, int $roleId)
    {
        $approvement = $application->approvement()
            ->where("user_id", $user->id)
            ->where("role_id", $roleId)
            ->first();

        $comments = [
            "comment" => $arrData["comment"] ?? " - "
        ];

        if ($approvement) {
            $approvement->update([
                "date" => Carbon::now(),
                "comments" => $comments,
            ]);
        } else {
            $approvement = $application->approvement()->create([
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
