<?php

namespace App\Http\Resources\ProjectMonitoring;

use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Policies\MonitoringTrfPolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MonitoringTableResource extends JsonResource
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
            "report.id" => $this->id,
            "proposal.project_number" => $this->project_number,
            "proposal.project_title" => $this->project_title,
            "proposal_researcher.name" => $this->name,
            "report_quarterly.approval_status" => $this->formatStatus($this->approval_status),
            "report_quarterly.report_type" => $this->formatType($this->report_type),
            "report_quarterly.updated_at" => $this->updated_at,
            "proposal.keywords" => optional($this->proposal)->keywords ? implode(", ", $this->proposal->keywords) : "",
            "report_quarterly.year" => $this->year,
            "report_quarterly.quarter" => "Quarter $this->quarter",
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
        $statusStr = ReportQuarterly::ARR_STATUS[$status] ?? " - ";

        return "<span class='$statusClass'>$statusStr</span>";
    }

    private function getArrButton()
    {
        $baseRoute = $this->proposal_type == Proposal::TYPE_TRF
            ? "panel.monitoring-trf"
            : "panel.monitoring-ef";

        $show = [
            "icon" => "fas fa-info",
            "url" => $this->report_type == ReportQuarterly::TYPE_MAR
                ? route($baseRoute . ".mar.show", ["mar" => $this->id])
                : route($baseRoute . ".qfr.show", ["qfr" => $this->id]),
            "label" => "Show Detail Report",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => $this->report_type == ReportQuarterly::TYPE_MAR
                ? route($baseRoute . ".mar.edit", ["mar" => $this->id])
                : route($baseRoute . ".qfr.edit", ["qfr" => $this->id]),
            "label" => "Edit Report",
            "classStyle" => "btn-outline-warning"
        ];

        $comment = [
            "icon" => "fas fa-comment-dots",
            "url" => $this->report_type == ReportQuarterly::TYPE_MAR
                ? route($baseRoute . ".mar.comment", ["mar" => $this->id])
                : route($baseRoute . ".qfr.comment", ["qfr" => $this->id]),
            "label" => "Comment Report",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => $this->report_type == ReportQuarterly::TYPE_MAR
                ? route($baseRoute . ".mar.delete", ["mar" => $this->id])
                : route($baseRoute . ".qfr.delete", ["qfr" => $this->id]),
            "label" => "Delete Report",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];

        $download = [
            "icon" => "fas fa-download",
            "url" => $this->report_type == ReportQuarterly::TYPE_MAR
                ? route($baseRoute . ".mar.download", ["mar" => $this->id])
                : route($baseRoute . ".qfr.download", ["qfr" => $this->id]),
            "label" => "Download Report",
            "classStyle" => "btn-outline-primary",
            "method" => "download"
        ];

        $user = Auth::user();
        $arrButton = [];

        $policy = new MonitoringTrfPolicy();
        if ($policy->view($user, $this->resource)) $arrButton[] = $show;
        if ($policy->update($user, $this->resource)) $arrButton[] = $edit;
        if ($policy->comment($user, $this->resource)) $arrButton[] = $comment;
        if ($policy->delete($user, $this->resource)) $arrButton[] = $delete;
        if ($policy->view($user, $this->resource)) $arrButton[] = $download;

        return $arrButton;
    }
}
