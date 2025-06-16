<?php

namespace App\Http\Resources\ApplicationManagement;

use App\Models\Approvement;
use App\Models\Proposal;
use App\Policies\ListOfApprovedPolicy;
use App\Policies\ListOfRejectedPolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ListOfProposalTableResource extends JsonResource
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
            "proposal.application_id" => $this->formatLinkProposal($this->application_id),
            "proposal.project_number" => $this->formatLinkProposal($this->project_number),
            "proposal.project_title" => $this->project_title,
            "proposal.proposal_type" => $this->proposal_type == 1 ? "TRF" : "External Fund",
            "proposal_researcher.name" => $this->name,
            "proposal.project_status" => $this->formatStatus($this->approval_status),
            "reviewer1.name" => $this->name_rev1,
            "reviewer2.name" => $this->name_rev2,
            "reviewer3.name" => $this->name_rev3,
            "proposal.updated_at" => $this->updated_at,
            "action" => $this->getArrButton()
        ];
    }

    private function formatLinkProposal($value)
    {
        $link = $this->proposal_type == Proposal::TYPE_TRF
            ? route("panel.trf.show", ["proposal" => $this->id])
            : route("panel.external-fund.show", ["proposal" => $this->id]);
        $value = $value ?? 'No-Data';
        return "<a href='$link' target='_blank'>$value</a>";
    }

    private function formatStatus($status)
    {
        $arrColorClass = [
            0 => 'text-secondary',
            1 => 'text-primary',
            2 => 'text-warning',
            3 => 'text-success',
            4 => 'text-warning',
            5 => 'text-danger'
        ];

        $statusClass = $arrColorClass[$this->project_status] ?? '';
        $statusStr = Approvement::ARR_STATUS[$status] ?? " - ";

        if ($status == Approvement::STATUS_APPROVED && $this->project_status != Proposal::STATUS_PRJ_PROPOSAL) {
            $statusStr =  Proposal::ARR_STATUS_PROJECT[$this->project_status] ?? $statusStr;
        }

        return "<span class='$statusClass'>$statusStr</span>";
    }

    private function getArrButton()
    {
        $show = [
            "icon" => "fas fa-info",
            "url" => route("panel.list-of-approved.show", ["proposal" => $this->id]),
            "label" => "Show Proposal",
            "classStyle" => "btn-outline-info"
        ];

        $editProposalUrl = $this->proposal_type == Proposal::TYPE_TRF
            ? route("panel.trf.edit", ["proposal" => $this->id])
            : route("panel.external-fund.edit", ["proposal" => $this->id]);

        $edit = [
            "icon" => "fas fa-edit",
            "url" => $this->approval_status == Approvement::STATUS_APPROVED
                ? route("panel.list-of-approved.edit", ["proposal" => $this->id])
                : $editProposalUrl,
            "label" => "Edit Proposal",
            "classStyle" => "btn-outline-warning"
        ];

        $user = Auth::user();
        $arrButton = [];

        $policy = $this->approval_status == Approvement::STATUS_APPROVED
            ? new ListOfApprovedPolicy()
            : new ListOfRejectedPolicy();

        if ($policy->view($user, $this->resource)) {
            $arrButton[] = $show;
        }
        if (
            $policy->revision($user, $this->resource)
            || $this->approval_status == Approvement::STATUS_DRAFT
        ) {
            $arrButton[] = $edit;
        }

        return $arrButton;
    }
}
