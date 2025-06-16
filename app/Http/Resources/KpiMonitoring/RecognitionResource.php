<?php

namespace App\Http\Resources\KpiMonitoring;

use App\Http\Resources\FileableResource;
use App\Models\Approvement;
use App\Models\Recognition;
use App\Policies\OutputRnDPolicy;
use App\Policies\RecognitionPolicy;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class RecognitionResource extends JsonResource
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
            "recognition.recognition_date" => Carbon::parse($this->recognition_date)->format("Y-m-d"),
            "recognition.project" => $this->project,
            "users.name" => $this->name,
            "kpi_achievement.approval_status" => $this->formatStatus($this->approval_status),
            "recognition.updated_at" => $this->updated_at,
            "action" => $this->getArrButton(),
            "files" => $this->getFiles($request)
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


    private function getFiles($request)
    {
        if ($this->fileable->count() <= 0) return " - ";

        $fileResource = (new FileableResource($this->fileable->first()))->toArray($request);
        return "<a href='{$fileResource["url"]}' download class='btn btn-sm btn-light'>
                <span class='material-icons'>attachment</span>
                Attachment
            </a>";
    }

    private function getArrButton()
    {
        $show = [
            "icon" => "fas fa-info",
            "url" => route("panel.recognition.show", ["recognition" => $this->id]),
            "label" => "Show Detail Recognition",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.recognition.edit", ["recognition" => $this->id]),
            "label" => "Edit Recognition",
            "classStyle" => "btn-outline-warning"
        ];

        $approvement = [
            "icon" => "fas fa-clipboard-check",
            "url" => route("panel.recognition.approvement", ["recognition" => $this->id]),
            "label" => "Approvement Recognition",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.recognition.delete", ["recognition" => $this->id]),
            "label" => "Delete Recognition",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];

        $arrButton = [];

        $user = Auth::user();

        $policy = new RecognitionPolicy();
        if ($policy->view($user, $this->resource)) $arrButton[] = $show;
        if ($policy->update($user, $this->resource)) $arrButton[] = $edit;
        if ($policy->approval($user, $this->resource)) $arrButton[] = $approvement;
        if ($policy->delete($user, $this->resource)) $arrButton[] = $delete;

        return $arrButton;
    }
}
