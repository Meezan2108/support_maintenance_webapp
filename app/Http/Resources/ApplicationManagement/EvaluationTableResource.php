<?php

namespace App\Http\Resources\ApplicationManagement;

use App\Models\Proposal;
use App\Policies\EvaluationPolicy;
use App\Policies\ProposalTrfPolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class EvaluationTableResource extends JsonResource
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
            "proposal.application_id" => $this->formatLinkProposal(),
            "proposal.project_title" => $this->project_title,
            "proposal.proposal_type" => $this->proposal_type == 1 ? "TRF" : "External Fund",
            "proposal_researcher.name" => $this->name,
            "proposal.approval_status" => $this->formatStatus($this->approval_status),
            "reviewer1.name" => $this->name_rev1,
            "reviewer2.name" => $this->name_rev2,
            "reviewer3.name" => $this->name_rev3,
            "proposal.updated_at" => $this->updated_at,
            "action" => $this->getArrButton()
        ];
    }

    private function formatLinkProposal()
    {
        $link = $this->proposal_type == 1
            ? route("panel.trf.show", ["proposal" => $this->id])
            : route("panel.external-fund.show", ["proposal" => $this->id]);

        return "<a href='$link' target='_blank'>$this->application_id</a>";
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
        $statusStr = Proposal::ARR_STATUS[$status] ?? " - ";

        return "<span class='$statusClass'>$statusStr</span>";
    }

    private function getArrButton()
    {
        $show = [
            "icon" => "fas fa-info",
            "url" => route("panel.technical-evaluation.show", ["proposal" => $this->id]),
            "label" => "Show Technical Evaluation",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.technical-evaluation.edit", ["proposal" => $this->id]),
            "label" => "Edit Technical Evaluation",
            "classStyle" => "btn-outline-warning"
        ];

        $user = Auth::user();
        $arrButton = [];

        $policy = new EvaluationPolicy();
        if ($policy->view($user, $this->resource)) $arrButton[] = $show;
        if ($policy->update($user, $this->resource)) $arrButton[] = $edit;

        return $arrButton;
    }
}
