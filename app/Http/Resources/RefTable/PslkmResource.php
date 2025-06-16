<?php

namespace App\Http\Resources\RefTable;

use App\Models\RefPslkm;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PslkmResource extends JsonResource
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
            "code" => $this->code,
            "description" => $this->description,
            "status" => $this->formatStatus(),
            "updated_at" => $this->updated_at,
            "action" => $this->getArrButton()
        ];
    }


    private function formatStatus()
    {
        $arrColorClass = [
            -1 => 'bg-secondary',
            1 => 'bg-success',
        ];

        $statusClass = $arrColorClass[$this->status] ?? 'text-secondary';
        $statusStr = RefPslkm::ARR_STATUS[$this->status] ?? ' - ';

        return "<span class='badge rounded-pill $statusClass'>$statusStr</span>";
    }

    private function getArrButton()
    {
        $show = [
            "icon" => "fas fa-info",
            "url" => route("panel.ref-table.pslkm.show", ["pslkm" => $this->id]),
            "label" => "Show Detail PSLKM",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.ref-table.pslkm.edit", ["pslkm" => $this->id]),
            "label" => "Edit PSLKM",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.ref-table.pslkm.delete", ["pslkm" => $this->id]),
            "label" => "Delete PSLKM",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];

        $arrButton = [];

        $user = Auth::user();

        $policy = new RefTablePolicy();
        if ($policy->view($user)) $arrButton[] = $show;
        if ($policy->update($user)) $arrButton[] = $edit;
        if ($policy->delete($user)) $arrButton[] = $delete;

        return $arrButton;
    }
}
