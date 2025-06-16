<?php

namespace App\Actions\ProjectMonitoring\Mar;

use App\Models\Proposal;
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
    public function execute(ReportQuarterly $report, User $user, array $arrData, int $roleId)
    {
        $approvement = $report->approvement()
            ->where("user_id", $user->id)
            ->where("role_id", $roleId)
            ->first();

        $comments = [
            'milestone_achievement' => $arrData['milestone_achievement'] ?? " - ",
            'project_achievement' => $arrData['project_achievement'] ?? " - ",
            'commentary' => $arrData['commentary'] ?? " - ",
        ];

        if ($approvement) {
            $approvement->update([
                "date" => Carbon::now(),
                "comments" => $comments,
            ]);
        } else {
            $approvement = $report->approvement()->create([
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
