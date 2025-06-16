<?php

namespace App\Http\Resources\KpiMonitoring;

use App\Models\Approvement;
use App\Policies\OutputRnDPolicy;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OutputRndResource extends JsonResource
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
            "output_rnd.date_output" => Carbon::parse($this->date_output)->format("Y-m-d"),
            "output_rnd.output" => $this->output,
            "users.name" => $this->name,
            "kpi_achievement.approval_status" => $this->formatStatus($this->approval_status),
            "output.updated_at" => $this->updated_at,
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
            "url" => route("panel.rnd-output.show", ["outputrnd" => $this->id]),
            "label" => "Show Detail Output R&D",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.rnd-output.edit", ["outputrnd" => $this->id]),
            "label" => "Edit Output R&D",
            "classStyle" => "btn-outline-warning"
        ];

        $approvement = [
            "icon" => "fas fa-clipboard-check",
            "url" => route("panel.rnd-output.approvement", ["outputrnd" => $this->id]),
            "label" => "Approvement R&D Output",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.rnd-output.delete", ["outputrnd" => $this->id]),
            "label" => "Delete Output R&D",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];

        $arrButton = [];

        $user = Auth::user();

        $policy = new OutputRnDPolicy();
        if ($policy->view($user, $this->resource)) $arrButton[] = $show;
        if ($policy->update($user, $this->resource)) $arrButton[] = $edit;
        if ($policy->approval($user, $this->resource)) $arrButton[] = $approvement;
        if ($policy->delete($user, $this->resource)) $arrButton[] = $delete;

        return $arrButton;
    }
}
