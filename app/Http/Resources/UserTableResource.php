<?php

namespace App\Http\Resources;

use App\Policies\UserPolicy;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserTableResource extends JsonResource
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
            "picture" => url('storage/' . $this->picture),
            "name" => $this->name,
            "email" => $this->email,
            "division" => $this->division,
            "position" => $this->position,
            "status" => $this->status == 1 ? "Active" : "Non-Active",
            "roles" => $this->roles->pluck("name")->join(", "),
            "created_at" => $this->created_at,
            "action" => $this->getArrButton()
        ];
    }

    private function getArrButton()
    {
        $show = [
            "icon" => "fas fa-info",
            "url" => route("panel.user.show", ["user" => $this->id]),
            "label" => "Show Detail User",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.user.edit", ["user" => $this->id]),
            "label" => "Edit User",
            "classStyle" => "btn-outline-warning"
        ];


        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.user.delete", ["user" => $this->id]),
            "label" => "Delete User",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];


        $user = Auth::user();
        $arrButton = [];

        $userPolicy = new UserPolicy();
        if ($userPolicy->view($user, $this->resource)) $arrButton[] = $show;
        if ($userPolicy->update($user, $this->resource)) $arrButton[] = $edit;
        if ($userPolicy->delete($user, $this->resource)) $arrButton[] = $delete;

        return $arrButton;
    }
}
