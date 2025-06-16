<?php

namespace App\Http\Resources\KpiMonitoring;

use App\Models\Approvement;
use App\Policies\AnalyticalServiceLabPolicy;
use App\Policies\ImportedGermplasmPolicy;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ImportedGermplasmResource extends JsonResource
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
            "imported_germplasm.year" => $this->year,
            "imported_germplasm.quarter" => $this->quarter ? 'Quarter ' . $this->quarter : '',
            "imported_germplasm.no_germplasm" => $this->no_germplasm,
            "users.name" => $this->name,
            "kpi_achievement.approval_status" => $this->formatStatus($this->approval_status),
            "imported_germplasm.updated_at" => $this->updated_at,
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
            "url" => route("panel.imported-germplasm.show", ["germplasm" => $this->id]),
            "label" => "Show Detail Imported Germplasm",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.imported-germplasm.edit", ["germplasm" => $this->id]),
            "label" => "Edit Imported Germplasm",
            "classStyle" => "btn-outline-warning"
        ];

        $approvement = [
            "icon" => "fas fa-clipboard-check",
            "url" => route("panel.imported-germplasm.approvement", ["germplasm" => $this->id]),
            "label" => "Approvement Imported Germplasm",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.imported-germplasm.delete", ["germplasm" => $this->id]),
            "label" => "Delete Imported Germplasm",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];

        $arrButton = [];

        $user = Auth::user();

        $policy = new ImportedGermplasmPolicy();
        if ($policy->view($user, $this->resource)) {
            $arrButton[] = $show;
        }

        if ($policy->update($user, $this->resource)) {
            $arrButton[] = $edit;
        }

        if ($policy->approval($user, $this->resource)) {
            $arrButton[] = $approvement;
        }

        if ($policy->delete($user, $this->resource)) {
            $arrButton[] = $delete;
        }

        return $arrButton;
    }
}
