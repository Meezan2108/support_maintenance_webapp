<?php

namespace App\Http\Resources\Documentation;

use App\Http\Resources\FileableResource;
use App\Models\Approvement;
use App\Models\Recognition;
use App\Policies\DocumentationPolicy;
use App\Policies\OutputRnDPolicy;
use App\Policies\RecognitionPolicy;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class DocumentationResource extends JsonResource
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
            "user.name" => $this->name,
            "ref_other_document.category_desc" => $this->category_desc,
            "documentation.description" => $this->description,
            "documentation.submission_date" => Carbon::parse($this->submission_date)->format("Y-m-d"),
            "documentation.updated_at" => $this->updated_at,
            "action" => $this->getArrButton(),
            "files" => $this->getFiles($request)
        ];
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
            "url" => route("panel.documentation.show", ["documentation" => $this->id]),
            "label" => "Show Detail Documentation",
            "classStyle" => "btn-outline-info"
        ];

        $edit = [
            "icon" => "fas fa-edit",
            "url" => route("panel.documentation.edit", ["documentation" => $this->id]),
            "label" => "Edit Documentation",
            "classStyle" => "btn-outline-warning"
        ];

        $delete = [
            "icon" => "fas fa-trash",
            "url" => route("panel.documentation.delete", ["documentation" => $this->id]),
            "label" => "Delete Documentation",
            "classStyle" => "btn-outline-danger",
            "method" => "delete"
        ];

        $download = [
            "icon" => "fas fa-download",
            "url" => route("panel.documentation.download", ["documentation" => $this->id]),
            "label" => "Download Documentation",
            "classStyle" => "btn-outline-primary",
            "method" => "download"
        ];

        $arrButton = [];

        $user = Auth::user();

        $policy = new DocumentationPolicy();
        if ($policy->view($user, $this->resource)) $arrButton[] = $show;
        if ($policy->update($user, $this->resource)) $arrButton[] = $edit;
        if ($policy->delete($user, $this->resource)) $arrButton[] = $delete;
        if ($policy->view($user, $this->resource)) $arrButton[] = $download;

        return $arrButton;
    }
}
