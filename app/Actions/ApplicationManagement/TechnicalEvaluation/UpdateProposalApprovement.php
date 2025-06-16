<?php

namespace App\Actions\ApplicationManagement\TechnicalEvaluation;

use App\Helpers\CommentHelper;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\User;
use Carbon\Carbon;

class UpdateProposalApprovement
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return Approvement
     */
    public function execute(Proposal $proposal, User $user, array $data, int $step)
    {
        $roleId = CommentHelper::determineRoleByStep($step);

        $approvement = $proposal->approvement()
            ->where("user_id", $user->id)
            ->where("role_id", $roleId)
            ->first();

        if ($approvement) {
            $approvement->update([
                "status" => $data["approval_status"] ?? null,
                "date" => Carbon::now(),
                "version" => $approvement->version + 1
            ]);
        } else {
            $approvement = $proposal->approvement()->create([
                "user_id" => $user->id,
                "role_id" => $roleId,
                "status" => $data["approval_status"] ?? null,
                "date" => Carbon::now(),
                "version" => 1
            ]);
        }

        return $approvement;
    }
}
