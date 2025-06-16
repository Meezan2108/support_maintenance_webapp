<?php

namespace App\Http\Resources\ProjectMonitoring;

use App\Http\Resources\FileableResource;
use App\Models\ReportMilestone;
use App\Models\ReportQuarterly;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitoringMarFormResource extends JsonResource
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
            "milestone_achievement" => $this->formatMilestoneAchievement($this->reportMilestone),
            "project_achievement" => $this->formatProjectAchievement($this->reportMilestone),
            "commentary" => $this->formatCommentary($this->reportMilestone),
            "attachment" => $this->formatAttachment($this->reportMilestone)
        ];
    }

    protected function formatMilestoneAchievement(ReportMilestone $report)
    {
        return [
            "proposal_id" => $this->proposal_id,
            "year" => $this->year,
            "quarter" => $this->quarter,
            "year_quarter" => $this->year . "-" . $this->quarter,

            'reason_not_achieved' =>  $report->reason_not_achieved,
            'corrective_action' =>  $report->corrective_action,
            'revised_completion_date' =>  $report->revised_completion_date
                ? substr($report->revised_completion_date, 0, 7)
                : '',

            'request_extension' => $report->request_extension,
            'new_completion_date' => $report->new_completion_date
                ? substr($report->new_completion_date, 0, 7)
                : '',
            'reason_for_extension' => $report->reason_for_extension,
        ];
    }

    protected function formatProjectAchievement(ReportMilestone $report)
    {
        return [
            "ipr" => $report->milestoneIpr,
            "publications" => $report->milestonePublication,
            "expertise_development" => $report->milestoneExpertiseDevelopment,
            "prototype" => $report->milestonePrototype,
            "commercialization" => $report->milestoneCommercialization
        ];
    }

    protected function formatCommentary(ReportMilestone $report)
    {
        return [
            "comments" => $report->comments
        ];
    }

    protected function formatAttachment(ReportMilestone $report)
    {
        return [
            "old_files" => FileableResource::collection($report->fileable->sortByDesc("id"))
        ];
    }
}
