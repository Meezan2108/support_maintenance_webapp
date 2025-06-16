<?php

namespace App\Http\Resources\KpiMonitoring;

use App\Models\Approvement;
use App\Models\KpiAchievement;
use App\Policies\MyKpiPolicy;
use App\Policies\OutputRnDPolicy;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MyKpiResource extends JsonResource
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
            "kpi_achievement.date" => Carbon::parse($this->date)->format("Y-m-d"),
            "kpi_achievement.title" => $this->title,
            "users.name" => $this->name,
            "kpi_achievement.category_id" => $this->description,
            "kpi_achievement.approval_status" => $this->formatStatus($this->approval_status),
            "kpi_achievement.updated_at" => $this->updated_at,
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
            "url" => route("panel.my-kpi.show", ["mykpi" => $this->id]),
            "label" => "Show Detail My KPI Achievement",
            "classStyle" => "btn-outline-info"
        ];

        $arrButton = [];

        $user = Auth::user();

        $policy = new MyKpiPolicy();
        if ($policy->view($user, $this->resource)) $arrButton[] = $show;

        return $arrButton;
    }
}
