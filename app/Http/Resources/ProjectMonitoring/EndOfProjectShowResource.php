<?php

namespace App\Http\Resources\ProjectMonitoring;

use App\Http\Resources\FileableResource;
use App\Models\Objectiveable;
use App\Models\ReportEndProject;
use Illuminate\Http\Resources\Json\JsonResource;

class EndOfProjectShowResource extends JsonResource
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
            "project_details" => $this->formatForm1($this->resource),
            "objectives_achievement" => $this->formatForm3($this->resource),
            "technology" => $this->formatForm4($this->resource),
            "assessment" => $this->formatForm5($this->resource),
            "additional_funding" => $this->formatForm6($this->resource),
            "benefits" => $this->formatForm7($this->resource),
            "report" => $this->formatForm8($this->resource),
        ];
    }

    protected function formatForm1(ReportEndProject $report)
    {
        return [
            "proposal_id" => $report->proposal_id,
            "proposal" => $report->proposal
        ];
    }

    protected function formatForm3(ReportEndProject $report)
    {
        $reportObjectives = $report->objective;

        return [
            "original_objectives" => $report->proposal->objectives,
            "objectives_achieved" => array_values($reportObjectives->where("status", Objectiveable::STATUS_ACHIEVED)->toArray()) ?? [],
            "objectives_not_achieved" => array_values($reportObjectives->where("status", Objectiveable::STATUS_NOT_ACHIEVED)->toArray()) ?? []
        ];
    }

    protected function formatForm4(ReportEndProject $report)
    {
        return [
            "tech_approach" => $report->tech_approach
        ];
    }

    protected function formatForm5(ReportEndProject $report)
    {
        return [
            "asses_research" => $report->asses_research,
            "asses_schedule" => $report->asses_schedule,
            "asses_cost" => $report->asses_cost
        ];
    }

    protected function formatForm6(ReportEndProject $report)
    {
        return [
            "additional_fund" => $report->additional_fund
        ];
    }

    protected function formatForm7(ReportEndProject $report)
    {
        $arrAnswer = [];
        foreach ($report->benefitAnswer as $answer) {
            $questionId = 'q_' . $answer->ref_report_eop_benefits_question_id;
            $arrAnswer[$questionId] = json_decode($answer->value);
        }

        return [
            "answers" => $arrAnswer
        ];
    }

    protected function formatForm8(ReportEndProject $report)
    {
        return [
            "old_files" => FileableResource::collection($report->fileable->sortByDesc("id"))
        ];
    }
}
