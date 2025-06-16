<?php

namespace App\Http\Resources\MyTask;

use App\Models\Approvement;
use Illuminate\Http\Resources\Json\JsonResource;

class MyTaskTableResource extends JsonResource
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
            "code_id" => $this->code_id,
            "title" => $this->title,
            "category" => $this->category,
            "approval_status" => $this->formatStatus($this->approval_status),
            "users.name" => $this->name,
            "updated_at" => $this->updated_at,
            "link" => $this->getLink(),
            "action" => $this->getArrButton()
        ];
    }

    private function formatStatus($status)
    {
        $arrColorClass = [
            0 => 'text-secondary',
            1 => 'text-primary',
            2 => 'text-warning',
            3 => 'text-warning',
            4 => 'text-success',
            5 => 'text-danger'
        ];

        $statusClass = $arrColorClass[$status] ?? '';
        $statusStr = Approvement::ARR_STATUS[$status] ?? " - ";

        return "<span class='$statusClass'>$statusStr</span>";
    }

    private function getLink()
    {
        return "<a href='{$this->link}'><span class='material-icons'>east</span></a>";
    }

    private function getArrButton()
    {
        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.my-task.delete", ["task" => $this->id]),
            "label" => "Delete Task",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];

        $arrButton = [];

        $arrButton[] = $delete;

        return $arrButton;
    }
}
