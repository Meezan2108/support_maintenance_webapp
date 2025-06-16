<?php

namespace App\Http\Resources\ProjectMonitoring;

use App\Models\ReportMilestone;
use App\Models\ReportQuarterlyFinancial;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitoringQfrFormResource extends JsonResource
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
            "project_details" => $this->projectDetails(),
            "financial_progress" => $this->financialProgress($this->reportQuarterlyFinancial),
            "budget_variations" => $this->budgetVariations($this->reportQuarterlyFinancial),
            "proposed_action" => $this->proposedAction($this->reportQuarterlyFinancial),
        ];
    }

    protected function projectDetails()
    {
        return [
            "proposal_id" => $this->proposal_id
        ];
    }

    protected function financialProgress(ReportQuarterlyFinancial $report)
    {
        return [
            "proposal_id" => $this->proposal_id,
            "year_quarter" => $this->year . "-" . $this->quarter,
            "year" => $this->year,
            "quarter" => $this->quarter,
            "total_recieved" => $report->total_recieved,
            "total_expenditure" => $report->total_expenditure,
            "actual_project_expenditure" => $report->detail,
            "is_inline_plan"  => $report->is_inline_plan
        ];
    }

    protected function budgetVariations(ReportQuarterlyFinancial $report)
    {
        return [
            "reasons" => $report->reasons
        ];
    }

    protected function proposedAction(ReportQuarterlyFinancial $report)
    {
        return [
            "proposed_action" => $report->proposed_action
        ];
    }
}
