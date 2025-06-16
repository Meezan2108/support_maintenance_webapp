<?php

namespace App\Http\Resources\Administrator;

use App\Models\Reminder;
use App\Policies\ReminderPolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ReminderTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "ref_reminder_category.description" => $this->description,
            "is_manual" => $this->getIsManual($this->is_manual),
            "when" => $this->getWhen(),
            "repeat" => !$this->is_manual ? $this->repeat_every . " " . $this->repeat_unit : ' - ',
            "status" => $this->status == 1 ? "Active" : "Non-Active",
            "notes" => strip_tags($this->notes),
            "updated_at" => $this->updated_at,
            "action" => $this->getArrButton()
        ];
    }

    private function getIsManual($value)
    {
        return $value
            ? '<span class="badge rounded-pill bg-secondary">Manual</span>'
            : '<span class="badge rounded-pill bg-success">Auto</span';
    }

    private function getWhen()
    {
        if ($this->is_manual) {
            return $this->scheduled_at;
        }

        $selRepeatType = Reminder::ARR_REPEAT_TYPE[$this->repeat_type] ?? false;

        return $selRepeatType ? $selRepeatType["description"] : '';
    }

    private function getArrButton()
    {
        $show = [
            "icon" => "fas fa-info",
            "url" => route("panel.reminder.show", ["reminder" => $this->id]),
            "label" => "Show Detail Reminder",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.reminder.edit", ["reminder" => $this->id]),
            "label" => "Edit Reminder",
            "classStyle" => "btn-outline-warning"
        ];


        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.reminder.delete", ["reminder" => $this->id]),
            "label" => "Delete Reminder",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];


        $user = Auth::user();
        $arrButton = [];

        $userPolicy = new ReminderPolicy();
        if ($userPolicy->view($user, $this->resource)) {
            $arrButton[] = $show;
        }
        if ($userPolicy->update($user, $this->resource)) {
            $arrButton[] = $edit;
        }
        if ($userPolicy->delete($user, $this->resource)) {
            $arrButton[] = $delete;
        }

        return $arrButton;
    }
}
