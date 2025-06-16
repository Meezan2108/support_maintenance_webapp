<?php

namespace App\Http\Resources\ManagementFund;

use App\Models\Approvement;
use App\Models\Proposal;
use App\Policies\ProposalTrfPolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ExternalFundTableResource extends JsonResource
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
            "proposal.id" => $this->id,
            "proposal.application_id" => $this->application_id,
            "proposal.project_title" => $this->project_title,
            "proposal_researcher.name" => $this->name,
            "proposal.approval_status" => $this->formatStatus($this->approval_status),
            "proposal.updated_at" => $this->updated_at,
            "proposal.keywords" => $this->keywords ? implode(", ", $this->keywords) : "",
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
            "url" => route("panel.external-fund.show", ["proposal" => $this->id]),
            "label" => "Show Detail Proposal",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.external-fund.edit", ["proposal" => $this->id]),
            "label" => "Edit Proposal",
            "classStyle" => "btn-outline-warning"
        ];

        $comment = [
            "icon" => "fas fa-comment-dots",
            "url" => route("panel.external-fund.comment", ["proposal" => $this->id]),
            "label" => "Comment Proposal",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.external-fund.delete", ["proposal" => $this->id]),
            "label" => "Delete Proposal",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];

        $download = [
            "icon" => "fas fa-download",
            "url" => route("panel.external-fund.download", ["proposal" => $this->id]),
            "label" => "Download Proposal",
            "classStyle" => "btn-outline-primary",
            "method" => "download"
        ];

        $user = Auth::user();
        $arrButton = [];

        $policy = new ProposalTrfPolicy();
        if ($policy->view($user, $this->resource)) $arrButton[] = $show;
        if ($policy->update($user, $this->resource)) $arrButton[] = $edit;
        if ($policy->comment($user, $this->resource)) $arrButton[] = $comment;
        if ($policy->delete($user, $this->resource)) $arrButton[] = $delete;
        if ($policy->view($user, $this->resource)) $arrButton[] = $download;

        return $arrButton;
    }
}
