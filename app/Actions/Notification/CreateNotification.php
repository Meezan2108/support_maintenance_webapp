<?php

namespace App\Actions\Notification;

use App\Models\Approvement;
use App\Models\Notifable;
use App\Models\RefDivision;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CreateNotification
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return Approvement
     */
    public function execute(
        Model $model,
        String $targetType,
        int $targetId,
        string $category,
        array $data,
        ?RefDivision $division = null
    ) {

        if (!$targetId) {
            return false;
        }

        $template = Template::query()
            ->where("category", $category)
            ->where("status", 1)
            ->first();

        if (!$template) return false;

        $notification = $model->notifable()->create([
            "template_id" => $template->id,
            "target_model_type" => $targetType,
            "target_model_id" => $targetId,
            "category" => $category,
            "ref_division_id" => optional($division)->id,
            "data" => $data
        ]);

        if ($targetType == Notifable::TARGET_TYPE_USER) {
            $user = User::find($targetId);
            (new ClearNotificationCache)->execute($user);
        }

        return $notification;
    }
}
