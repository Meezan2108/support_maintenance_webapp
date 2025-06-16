<?php

namespace App\Actions;

use App\Models\Approvement;
use App\Models\ApprovementStep;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UpdateApprovement
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(
        Model $model,
        Approvement $approvement,
        ApprovementStep $approvementStep,
        array $arrData
    ) {
        $approvement->status = $arrData["status"];
        $approvement->save();

        if (
            in_array($approvement->status, [
                Approvement::STATUS_AMEND,
                Approvement::STATUS_REJECTED
            ])
        ) {
            $model->approval_status = $approvement->status;
            $model->save();

            $approvementStep->step = 0;
            $approvementStep->save();
        }

        // if all approved
        if (
            $approvement->status == Approvement::STATUS_APPROVED
            && $approvementStep->step == 3
        ) {
            $model->approval_status = Approvement::STATUS_APPROVED;
            $model->save();
        }

        return $model;
    }
}
