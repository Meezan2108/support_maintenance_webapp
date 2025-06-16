<?php

namespace App\Http\Resources\KpiMonitoring;

use App\Models\Approvement;
use App\Policies\AnalyticalServiceLabPolicy;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AnalyticalServiceLabResource extends JsonResource
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
            "analytical_service_lab.date" => Carbon::parse($this->date)->format("Y-m-d"),
            "analytical_service_lab.year" => $this->year,
            "analytical_service_lab.quarter" => $this->quarter ? "Quarter " . $this->quarter : '',
            "analytical_service_lab.no_sample" => $this->no_sample,
            "analytical_service_lab.no_analysis" => $this->no_analysis,
            "analytical_service_lab.no_analysis_protocol" => $this->no_analysis_protocol,
            "users.name" => $this->name,
            "kpi_achievement.approval_status" => $this->formatStatus($this->approval_status),
            "analytical_service_lab.updated_at" => $this->updated_at,
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
            "url" => route("panel.analytical-service-lab.show", ["analytical" => $this->id]),
            "label" => "Show Detail Analytical Service Lab",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.analytical-service-lab.edit", ["analytical" => $this->id]),
            "label" => "Edit Analytical Service Lab",
            "classStyle" => "btn-outline-warning"
        ];

        $approvement = [
            "icon" => "fas fa-clipboard-check",
            "url" => route("panel.analytical-service-lab.approvement", ["analytical" => $this->id]),
            "label" => "Approvement Analytical Service Lab",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.analytical-service-lab.delete", ["analytical" => $this->id]),
            "label" => "Delete Analytical Service Lab",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];

        $arrButton = [];

        $user = Auth::user();

        $policy = new AnalyticalServiceLabPolicy();
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
