<?php

namespace App\Http\Resources\MonitoringFile;

use App\Http\Resources\FileableResource;
use App\Models\Approvement;
use App\Models\Recognition;
use App\Policies\DocumentationPolicy;
use App\Policies\OutputRnDPolicy;
use App\Policies\RecognitionPolicy;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MonitoringFileResource extends JsonResource
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
            "fileable.fileable_type" => $this->fileable_type,
            "fileable.fileable_id" => $this->fileable_id,
            "fileable.access_key" => $this->access_key,
            "fileable.code_type" => $this->code_type,
            "fileable.file_name" => $this->file_name,
            "fileable.file_type" => $this->file_type,
            "fileable.file_size" => (round($this->file_size/1000, 2)).' KB',
            "fileable.created_at" => $this->created_at,
            "fileable.updated_at" => Carbon::parse($this->updated_at)->format("Y-m-d"),
            "files" => $this->getFiles()
        ];
    }

    private function getFiles()
    {
        $url = route('resources.fileable.show', [
            "fileable" => $this->id,
            "access_key" => $this->access_key
        ]);
        
        return "<a href='{$url}' download class='btn btn-sm btn-light'>
                <span class='material-icons'>attachment</span>
                Attachment
            </a>";
    }
}
