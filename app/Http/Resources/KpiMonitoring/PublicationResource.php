<?php

namespace App\Http\Resources\KpiMonitoring;

use App\Models\Approvement;
use App\Models\Publication;
use App\Policies\PublicationPolicy;
use App\Policies\RefTablePolicy;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PublicationResource extends JsonResource
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
            "user_id" => $this->user_id,
            "publication.date_published" => Carbon::parse($this->date_published)->format("Y-m-d"),
            "publication.title" => $this->title,
            "users.name" => $this->name,
            "kpi_achievement.approval_status" => $this->formatStatus($this->approval_status),
            "publication.updated_at" => $this->updated_at,
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

    private function getArrButton()
    {
        $show = [
            "icon" => "fas fa-info",
            "url" => route("panel.publications.show", ["publication" => $this->id]),
            "label" => "Show Detail Publication",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.publications.edit", ["publication" => $this->id]),
            "label" => "Edit Publication",
            "classStyle" => "btn-outline-warning"
        ];

        $approvement = [
            "icon" => "fas fa-clipboard-check",
            "url" => route("panel.publications.approvement", ["publication" => $this->id]),
            "label" => "Approvement Publication",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.publications.delete", ["publication" => $this->id]),
            "label" => "Delete Publication",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];

        $arrButton = [];

        $user = Auth::user();

        $policy = new PublicationPolicy();
        if ($policy->view($user, $this->resource)) $arrButton[] = $show;
        if ($policy->update($user, $this->resource)) $arrButton[] = $edit;
        if ($policy->approval($user, $this->resource)) $arrButton[] = $approvement;
        if ($policy->delete($user, $this->resource)) $arrButton[] = $delete;

        return $arrButton;
    }
}
