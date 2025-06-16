<?php

namespace App\Http\Resources\Administrator;

use App\Models\Reminder;
use Illuminate\Http\Resources\Json\JsonResource;

class ReminderResource extends JsonResource
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
            "is_manual" => $this->is_manual,
            "is_manual_text" => $this->is_manual ? "Manual" : "Auto",
            "ref_reminder_category_id" => $this->ref_reminder_category_id,
            "ref_reminder_category_text" => optional($this->category)->description,
            "scheduled_at" => $this->scheduled_at,
            "repeat_type" => $this->repeat_type,
            "repeat_type_text" => Reminder::ARR_REPEAT_TYPE[$this->repeat_type]["description"] ?? "",
            "options" => $this->options,
            "notes" => $this->notes,
            "status" => $this->status,
            "status_text" => $this->status ? "Active" : "Disable",
        ];
    }
}
