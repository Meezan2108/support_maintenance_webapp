<?php

namespace App\Http\Resources;

use App\Policies\RolePolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RoleResource extends JsonResource
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
            "name" => $this->name,
            "updated_at" => $this->updated_at,
            "action" => $this->getArrButton()
        ];
    }

    private function getArrButton()
    {
        $user = Auth::user();

        $show = [
            "icon" => "fas fa-info",
            "url" => route("panel.role.show", ["role" => $this->id]),
            "label" => "Show Detail User",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.role.edit", ["role" => $this->id]),
            "label" => "Edit User",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.role.delete", ["role" => $this->id]),
            "label" => "Delete User",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];

        $arrButton = [];

        $rolePolicy = new RolePolicy();
        if ($rolePolicy->view($user, $this->resource)) $arrButton[] = $show;
        if ($rolePolicy->update($user, $this->resource)) $arrButton[] = $edit;
        if ($rolePolicy->delete($user, $this->resource)) $arrButton[] = $delete;

        return $arrButton;
    }
}
