<?php

namespace App\Actions;

use App\Actions\MyTask\ClearMyTaskCache;
use App\Models\RefDivision;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateTask
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(
        Model $model,
        User $submitedUser,
        int $targetId,
        string $targetType,
        string $link,
        string $idModel,
        string $title,
        string $category,
        int $status,
        ?RefDivision $division = null,
        array $options = []
    ) {
        $task = $model->taskable;
        $arrTask = [
            "code_type" => $targetType,
            "model_id" => $targetId,
            "submited_user_id" => $submitedUser->id,
            "code_id" => $idModel,
            "title" => $title,
            "category" => $category,
            "link" => $link,
            "approval_status" => $status,
            "options" => $options,
            "ref_division_id" => optional($division)->id
        ];

        if ($task) {
            $task->update($arrTask);
        } else {
            $task = $model->taskable()->create($arrTask);
        }

        if ($targetType == "user") {
            (new ClearMyTaskCache)->execute($targetId);
        }

        (new ClearMyTaskCache)->execute(Auth::id());
        return $task;
    }
}
