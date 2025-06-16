<?php

namespace App\Http\Resources\KpiMonitoring;

use App\Models\Approvement;
use App\Models\User;
use App\Policies\TargetKpiGlobalPolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TargetKpiGlobalResource extends JsonResource
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
            "target_kpi.year" => $this->year,
            "target_kpi.category_id" => $this->category->description
                . ($this->subCategory ? " / " . $this->subCategory->description : ''),
            "target_kpi.target" => $this->target,
            "target_kpi.approval_status" => $this->formatStatus($this->approval_status),
            "target_kpi.updated_at" => $this->updated_at,
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
            "url" => route("panel.target-kpi-global.show", ["target" => $this->id]),
            "label" => "Show Agency KPI Target",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.target-kpi-global.edit", ["target" => $this->id]),
            "label" => "Edit Agency KPI Target",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.target-kpi-global.delete", ["target" => $this->id]),
            "label" => "Delete Agency KPI Target",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];

        $arrButton = [];

        /**
         * @var User
         */
        $user = Auth::user();

        $policy = new TargetKpiGlobalPolicy();
        if ($policy->view($user, $this->resource)) {
            $arrButton[] = $show;
        }

        if ($policy->update($user, $this->resource)) {
            $arrButton[] = $edit;
        }

        if ($policy->delete($user, $this->resource)) {
            $arrButton[] = $delete;
        }

        return $arrButton;
    }
}
