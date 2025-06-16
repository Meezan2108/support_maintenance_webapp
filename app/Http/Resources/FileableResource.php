<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileableResource extends JsonResource
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
            "code_type" => $this->code_type,
            "file_name" => $this->file_name,
            "url" => route('resources.fileable.show', [
                "fileable" => $this->id,
                "access_key" => $this->access_key
            ]),
            "file_type" => $this->file_type,
            "file_size" => $this->file_size,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
