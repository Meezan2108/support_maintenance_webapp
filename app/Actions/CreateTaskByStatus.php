<?php

namespace App\Actions;

use App\Actions\MyTask\ClearMyTaskCache;
use App\Helpers\CommentHelper;
use App\Models\Approvement;
use App\Models\ApprovementStep;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateTaskByStatus
{


    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(
        Approvement $approvement,
        ApprovementStep $approvementStep,
        string $linkApproved,
        string $linkAmend,
        string $codeId,
        string $title,
        string $category
    ) {
        if (!in_array($approvement->status, [Approvement::STATUS_APPROVED, Approvement::STATUS_AMEND])) {
            return null;
        }

        $submitedUser = User::find(Auth::id());
        $model = $approvement->reference;

        if ($approvement->status == Approvement::STATUS_AMEND) {
            $targetType = "user";
            $targetId = $model->user->id;
            $link = $linkAmend;
        }

        if ($approvement->status == Approvement::STATUS_APPROVED) {
            $targetType = "group";
            $targetId = CommentHelper::determineRoleByStep($approvementStep->step);
            $link = $linkApproved;
        }

        $task = $model->taskable;
        $arrTask = [
            "code_type" => $targetType,
            "model_id" => $targetId,
            "submited_user_id" => $submitedUser->id,
            "code_id" => $codeId,
            "title" => $title,
            "category" => $category,
            "link" => $link,
            "approval_status" => $model->approval_status,
            "options" => []
        ];

        if ($task) {
            $task->update($arrTask);
        } else {
            $model->taskable()->create($arrTask);
        }

        if ($targetType == "user") {
            (new ClearMyTaskCache)->execute($targetId);
        }

        (new ClearMyTaskCache)->execute(Auth::id());

        return $task;
    }
}
