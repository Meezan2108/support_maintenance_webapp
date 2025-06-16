<?php

namespace App\Http\Resources\RefTable;

use App\Policies\RefTablePolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ForAreaResource extends JsonResource
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
            "ref_for_area.id" => $this->id,
            "ref_for_area.code" => $this->code,
            "ref_for_area.description" => $this->description,
            "ref_for_group.description" => optional($this->group)->description,
            "ref_for_area.updated_at" => $this->updated_at,
            "group" => $this->group,
            "action" => $this->getArrButton()
        ];
    }

    private function getArrButton()
    {
        $show = [
            "icon" => "fas fa-info",
            "url" => route("panel.ref-table.for-area.show", ["area" => $this->id]),
            "label" => "Show Detail User",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.ref-table.for-area.edit", ["area" => $this->id]),
            "label" => "Edit User",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.ref-table.for-area.delete", ["area" => $this->id]),
            "label" => "Delete User",
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
