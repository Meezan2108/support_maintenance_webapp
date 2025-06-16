<?php

namespace App\Http\Resources\RefTable;

use App\Policies\RefTablePolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SeoGroupResource extends JsonResource
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
            "ref_seo_group.id" => $this->id,
            "ref_seo_group.code" => $this->code,
            "ref_seo_group.description" => $this->description,
            "ref_seo_category.description" => optional($this->category)->description,
            "ref_seo_group.updated_at" => $this->updated_at,
            "category" => $this->category,
            "action" => $this->getArrButton()
        ];
    }

    private function getArrButton()
    {
        $show = [
            "icon" => "fas fa-info",
            "url" => route("panel.ref-table.seo-group.show", ["group" => $this->id]),
            "label" => "Show Detail SEO Group",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.ref-table.seo-group.edit", ["group" => $this->id]),
            "label" => "Edit SEO Group",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.ref-table.seo-group.delete", ["group" => $this->id]),
            "label" => "Delete SEO Group",
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
