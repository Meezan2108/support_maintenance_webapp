<?php

namespace App\Http\Resources\VueSelect;

use Illuminate\Http\Resources\Json\JsonResource;

class RefSelectDefaultResource extends JsonResource
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
            "description" => $this->description
        ];
    }
}
