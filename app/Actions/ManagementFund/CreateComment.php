<?php

namespace App\Actions\ManagementFund;

use App\Models\Proposal;
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
    public function execute(Proposal $proposal, User $user, array $arrData, int $roleId)
    {
        $approvement = $proposal->approvement()
            ->where("user_id", $user->id)
            ->where("role_id", $roleId)
            ->first();

        $comments = [
            'identification' => $arrData['identification'] ?? " - ",
            'objectives' => $arrData['objectives'] ?? " - ",
            'research_background' => $arrData['research_background'] ?? " - ",
            'research_approach' => $arrData['research_approach'] ?? " - ",
            'project_schedule' => $arrData['project_schedule'] ?? " - ",
            'benefits' => $arrData['benefits'] ?? " - ",
            'research_collabration' => $arrData['research_collabration'] ?? " - ",
            'expenses_estimation' => $arrData['expenses_estimation'] ?? " - ",
            'project_cost' => $arrData['project_cost'] ?? " - ",
        ];

        if ($approvement) {
            $approvement = $approvement->update([
                "date" => Carbon::now(),
                "comments" => $comments
            ]);
        } else {
            $approvement = $proposal->approvement()->create([
                "user_id" => $user->id,
                "role_id" => $roleId,
                "status" => 0,
                "date" => Carbon::now(),
                "comments" => $comments,
                "version" => 1
            ]);
        }

        return $proposal;
    }
}
