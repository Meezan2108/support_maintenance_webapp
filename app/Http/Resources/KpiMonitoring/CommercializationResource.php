<?php

namespace App\Http\Resources\KpiMonitoring;

use App\Models\Approvement;
use App\Policies\CommercializationPolicy;
use App\Policies\OutputRnDPolicy;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CommercializationResource extends JsonResource
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
            "commercialization.date" => Carbon::parse($this->date)->format("Y-m-d"),
            "commercialization.name" => $this->name_product,
            "users.name" => $this->name,
            "kpi_achievement.approval_status" => $this->formatStatus($this->approval_status),
            "commercialization.updated_at" => $this->updated_at,
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
            "url" => route("panel.commercialization.show", ["commercialization" => $this->id]),
            "label" => "Show Detail Commercialization",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.commercialization.edit", ["commercialization" => $this->id]),
            "label" => "Edit Commercialization",
            "classStyle" => "btn-outline-warning"
        ];

        $approvement = [
            "icon" => "fas fa-clipboard-check",
            "url" => route("panel.commercialization.approvement", ["commercialization" => $this->id]),
            "label" => "Approvement Commercialization",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.commercialization.delete", ["commercialization" => $this->id]),
            "label" => "Delete Commercialization",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];

        $arrButton = [];

        $user = Auth::user();

        $policy = new CommercializationPolicy();
        if ($policy->view($user, $this->resource)) $arrButton[] = $show;
        if ($policy->update($user, $this->resource)) $arrButton[] = $edit;
        if ($policy->approval($user, $this->resource)) $arrButton[] = $approvement;
        if ($policy->delete($user, $this->resource)) $arrButton[] = $delete;

        return $arrButton;
    }
}
