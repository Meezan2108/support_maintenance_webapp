<?php

namespace App\Http\Resources\RefTable;

use App\Policies\RefTablePolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ResearchClusterResource extends JsonResource
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
            "url" => route("panel.ref-table.research-cluster.show", ["cluster" => $this->id]),
            "label" => "Show Detail Cluster",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.ref-table.research-cluster.edit", ["cluster" => $this->id]),
            "label" => "Edit Cluster",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.ref-table.research-cluster.delete", ["cluster" => $this->id]),
            "label" => "Delete Cluster",
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
