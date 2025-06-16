<?php

namespace App\Http\Resources;

use App\Http\Resources\RefTable\RefDivisionResource;
use App\Http\Resources\RefTable\RefPositionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $picture = $this->fileable
            ? route('resources.fileable.show', [
                "fileable" => $this->fileable->id,
                "access_key" => $this->fileable->access_key
            ])
            : '';

        return [
            "id" => $this->id,
            "staf_id" => $this->staf_id,
            "picture" => $picture,
            "name" => $this->name,
            "nric" => $this->nric,
            "email" => $this->email,
            "salutation" => $this->salutation,
            "working_address" => $this->working_address,
            "qualification" => $this->qualification,
            "ref_division_id" => $this->ref_division_id,
            "division" => $this->whenLoaded('division', new RefDivisionResource($this->division)),
            "ref_position_id" => $this->ref_position_id,
            "position" => $this->whenLoaded('position', new RefPositionResource($this->position)),
            "status" => $this->status,
            "status_text" => $this->status ? "Active" : "Non-active",
            "researcher_id" => $this->researcher_id,
            "roles" => RoleDetailResource::collection($this->whenLoaded('roles')),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
