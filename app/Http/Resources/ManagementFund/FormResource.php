<?php

namespace App\Http\Resources\ManagementFund;

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
            "id" => $this->id,
            "proposal_type" => $this->proposal_type,
            "identification" => $this->getIdentification(),
            "objectives" => $this->getObjectives(),
            "research_background" => $this->getResearchBackground(),
            "research_approach" => $this->getResearchApproach(),
            "project_schedule" => null,
            "benefits" => $this->getBenefits(),
            "research_collabration" => $this->getResearchCollaboration(),
            "expenses_estimation" => $this->getExpensesEstimation(),
            "project_cost" => null,
        ];
    }

    public function getIdentification()
    {
        return [
            "id" => $this->id,
            "application_id" => $this->application_id,
            'project_leader_type' => $this->project_leader_type,
            'proposal_type' => $this->proposal_type,
            'ref_type_of_funding_id' => $this->ref_type_of_funding_id,
            'project_title' => $this->project_title,
            'user_id' => $this->user_id,
            'researcher' => $this->researcher,
            'working_address' => $this->working_address,
            'institution' => $this->institution,
            'grade' => $this->grade,
            'keywords' => $this->keywords
        ];
    }

    public function getObjectives() // form2
    {
        return [
            "id" => $this->id,
            'ref_research_type_id' => $this->ref_research_type_id,
            'ref_research_cluster_id' => $this->ref_research_cluster_id,
            'objectives' => $this->objectives,
            'ref_seo_category_id' => $this->ref_seo_category_id,
            'ref_seo_group_id' => $this->ref_seo_group_id,
            'ref_seo_area_id' => $this->ref_seo_area_id,
            'for_primary' => $this->primaryResearchField,
            'for_secondary' => $this->secondaryResearchField
        ];
    }

    public function getResearchBackground() // form3
    {
        return [
            "id" => $this->id,
            'research_location' => $this->research_location,
            'project_summary' => $this->project_summary,
            'problem_statement' => $this->problem_statement,
            'hypothesis' => $this->hypothesis,
            'research_question' => $this->research_question,
            'literature_review' => $this->literature_review,
            'relevance_goverment_policy' => $this->relevance_goverment_policy,
            'reference' => $this->reference,
            'related_research' => $this->related_research,
        ];
    }

    public function getResearchApproach() // form4
    {
        return [
            "id" => $this->id,
            'research_methodology' => $this->research_methodology,
            'risk_factor' => $this->risk_factor,
            'risk_technical' => $this->risk_technical,
            'risk_budget' => $this->risk_budget,
            'risk_timing' => $this->risk_timing,

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
            'milestones' => $this->milestones,
        ];
    }

    public function getBenefits()
    {
        return [
            "id" => $this->id,
            "economic_contributions" => $this->economicContribution,
            "output_expected" => $this->benefits()->outputExpected()->get(),
            "human_capital" => $this->benefits()->humanCapital()->get(),
        ];
    }

    public function getResearchCollaboration()
    {
        $collaborations = $this->collaborations;
        $teams = $this->teams;

        return [
            "id" => $this->id,
            "organizations" => array_values($collaborations->where("type", 1)->toArray()),
            "industries" => array_values($collaborations->where("type", 2)->toArray()),
            "project_leaders" => array_values($teams->where("type", 1)->toArray()),
            "researchers" => array_values($teams->where("type", 2)->toArray()),
            "staffs" => array_values($teams->where("type", 3)->toArray())
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
}
