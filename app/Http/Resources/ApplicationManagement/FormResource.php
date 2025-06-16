<?php

namespace App\Http\Resources\ApplicationManagement;

use App\Http\Resources\FileableResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
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
            "timeline" => $this->getTimeline(),
            "expenses_estimation" => $this->getExpensesEstimation(),
            "project_cost" => null,
            "documentation" => $this->getDocumentation(),
            "status" => $this->getStatus(),
        ];
    }

    public function getTimeline()
    {
        return [
            "id" => $this->id,
            "application_id" => $this->application_id,

            'project_leader_type' => $this->project_leader_type,
            'proposal_type' => $this->proposal_type,
            'ref_type_of_funding_id' => $this->ref_type_of_funding_id,

            "ptj_code" => $this->ptj_code,
            "project_number" => $this->project_number,

            'project_title' => $this->project_title,
            'user_id' => $this->user_id,
            'researcher' => $this->researcher,
            'working_address' => $this->working_address,

            'schedule_start_date' => substr($this->schedule_start_date, 0, 7),
            'schedule_duration' => $this->schedule_duration,

            'activities' => $this->activities->map(function ($item) {
                return [
                    "id" => $item->id,
                    "proposal_id" => $item->proposal_id,
                    "activities" => $item->activities,
                    "from" => substr($item->from, 0, 7),
                    "to" => substr($item->to, 0, 7),
                    "order" => $item->order
                ];
            }),
            'milestones' => $this->milestones->map(function ($item) {
                return [
                    "id" => $item->id,
                    "proposal_id" => $item->proposal_id,
                    "activities" => $item->activities,
                    "from" => substr($item->from, 0, 7),
                    "to" => substr($item->from, 0, 7),
                    "order" => $item->order
                ];
            }),
        ];
    }

    public function getExpensesEstimation()
    {
        $projectCost = $this->projectCost->load("detail");

        $projectCost = $projectCost->map(function ($item) {
            $item->years = $item->detail->sortBy([["year", "asc"]])->pluck("cost")->toArray();
            return $item;
        });

        return [
            "id" => $this->id,
            "V21000" => array_values($projectCost->where("ref_project_cost_series_id", 2)->toArray()),
            "V26000" => array_values($projectCost->where("ref_project_cost_series_id", 4)->toArray()),
            "V28000" => array_values($projectCost->where("ref_project_cost_series_id", 5)->toArray()),
            "V29000" => array_values($projectCost->where("ref_project_cost_series_id", 6)->toArray()),
            "V11000" => array_values($projectCost->where("ref_project_cost_series_id", 1)->toArray())
        ];
    }

    protected function getDocumentation()
    {
        return [
            "old_files" => FileableResource::collection($this->resource->fileable->sortByDesc("id"))
        ];
    }

    protected function getStatus()
    {
        return [
            "id" => $this->id,
            "status" => $this->project_status
        ];
    }
}
