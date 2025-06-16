<?php

namespace App\Http\Resources\KpiMonitoring;

use Illuminate\Http\Resources\Json\JsonResource;

class TargetKpiByResearcherResource extends JsonResource
{

    /**
     * Transform the resource into an array.🙏
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "target_kpi.year" => $this->year,
            "users.name" => $this->name,
            "action" => $this->getArrButton()
        ];
    }

    private function getArrButton()
    {
        $show = [
            "icon" => "fas fa-download",
            "url" => route("panel.target-kpi.download.show", [
                "year" => $this->year,
                "researcher_id" => $this->user_id
            ]),
            "label" => "Download KPI " . $this->year,
            "classStyle" => "btn-outline-primary",
            "method" => "download"
        ];

        $arrButton = [$show];

        return $arrButton;
    }
}
