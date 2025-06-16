<?php

namespace App\Http\Resources\RefTable;

use App\Policies\RefTablePolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ResearchTypeResource extends JsonResource
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
            "updated_at" => $this->updated_at,
            "action" => $this->getArrButton()
        ];
    }

    private function getArrButton()
    {
        $show = [
            "icon" => "fas fa-info",
            "url" => route("panel.ref-table.research-type.show", ["type" => $this->id]),
            "label" => "Show Detail Research Type",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.ref-table.research-type.edit", ["type" => $this->id]),
            "label" => "Edit Research Type",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.ref-table.research-type.delete", ["type" => $this->id]),
            "label" => "Delete Research Type",
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
