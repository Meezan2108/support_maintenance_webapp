<?php

namespace App\Http\Resources\ProjectMonitoring;

use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Policies\ExtensionProjectPolicy;
use App\Policies\MonitoringTrfPolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ExtensionProjectTableResource extends JsonResource
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
            "extension_project.approval_status" => $this->formatStatus($this->approval_status),
            "extension_project.updated_at" => $this->updated_at,
            "proposal.keywords" => $this->proposal->keywords ? implode(", ", $this->proposal->keywords) : "",
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
        $show = [
            "icon" => "fas fa-info",
            "url" => route("panel.extension-project.show", ["application" => $this->id]),
            "label" => "Show Detail Report",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.extension-project.edit", ["application" => $this->id]),
            "label" => "Edit Report",
            "classStyle" => "btn-outline-warning"
        ];

        $comment = [
            "icon" => "fas fa-comment-dots",
            "url" => route("panel.extension-project.comment", ["application" => $this->id]),
            "label" => "Comment Report",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.extension-project.delete", ["application" => $this->id]),
            "label" => "Delete Report",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];

        $download = [
            "icon" => "fas fa-download",
            "url" => route("panel.extension-project.download", ["application" => $this->id]),
            "label" => "Download Report",
            "classStyle" => "btn-outline-primary",
            "method" => "download"
        ];

        $user = Auth::user();
        $arrButton = [];

        $policy = new ExtensionProjectPolicy();
        if ($policy->view($user, $this->resource)) $arrButton[] = $show;
        if ($policy->update($user, $this->resource)) $arrButton[] = $edit;
        if ($policy->comment($user, $this->resource)) $arrButton[] = $comment;
        if ($policy->delete($user, $this->resource)) $arrButton[] = $delete;
        if ($policy->view($user, $this->resource)) $arrButton[] = $download;

        return $arrButton;
    }
}
