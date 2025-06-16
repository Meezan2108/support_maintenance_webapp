<?php

namespace App\Http\Resources\RefTable;

use App\Policies\RefTablePolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SeoCategoryResource extends JsonResource
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
            "ref_seo_category.id" => $this->id,
            "ref_seo_category.code" => $this->code,
            "ref_seo_category.description" => $this->description,
            "ref_seo_sector.description" => optional($this->sector)->description,
            "ref_seo_category.updated_at" => $this->updated_at,
            "sector" => $this->sector,
            "action" => $this->getArrButton()
        ];
    }

    private function getArrButton()
    {
        $show = [
            "icon" => "fas fa-info",
            "url" => route("panel.ref-table.seo-category.show", ["category" => $this->id]),
            "label" => "Show Detail SEO Category",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.ref-table.seo-category.edit", ["category" => $this->id]),
            "label" => "Edit SEO Category",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.ref-table.seo-category.delete", ["category" => $this->id]),
            "label" => "Delete SEO Category",
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
