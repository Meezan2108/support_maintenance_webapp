<?php

namespace App\Actions\KpiMonitoring;

use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\Publication;
use App\Models\RefPubType;
use App\Models\RefStatusProject;
use App\Models\RefTypeOfFunding;
use App\Models\ReportEndProject;
use App\Models\ReportQuarterly;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PreparePrintProposal
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(
        int $year,
        ?User $researcher,
        Collection $targetCategories,
        Collection $targetPeriod,
        Collection $targets
    ) {
        $typeOfFundings = RefTypeOfFunding::withCount([
            "proposal as completed_count" => function ($query) use ($year) {
                return $query->whereHas("reportEndProject", function ($query) use ($year) {
                    $mdlReportEndProject = new ReportEndProject();
                    $query->whereRaw("YEAR([report_end_project].[created_at]) = ?", [$year])
                        ->where($mdlReportEndProject->qualifyColumn("approval_status"), Approvement::STATUS_APPROVED);
                });
            },
            "proposal as ongoing_count" => function ($query) use ($year) {
                return $query->whereDoesntHave("reportEndProject", function ($query) use ($year) {
                    $mdlReportEndProject = new ReportEndProject();
                    $query->whereRaw("YEAR([report_end_project].[created_at]) = ?", [$year])
                        ->where($mdlReportEndProject->qualifyColumn("approval_status"), Approvement::STATUS_APPROVED);
                })->whereHas("reportQuarterly", function ($query) use ($year) {
                    $mdlReportQuarterly = new ReportQuarterly();
                    $query->where($mdlReportQuarterly->qualifyColumn("year"), $year)
                        ->where($mdlReportQuarterly->qualifyColumn("approval_status"), Approvement::STATUS_APPROVED);
                });
            },
            "proposal as pending_count" => function ($query) use ($year) {
                return $query->where("approval_status", Approvement::STATUS_APPROVED)
                    ->where(function ($query) use ($year) {
                        $query->where(DB::raw("YEAR([rmc_proceed_at])"), ">", $year)
                            ->orWhereNull("rmc_proceed_at");
                    })->whereDoesntHave("reportQuarterly", function ($query) use ($year) {
                        $mdlReportQuarterly = new ReportQuarterly();
                        $query->where($mdlReportQuarterly->qualifyColumn("year"), $year);
                    });
            }
        ])->get();

        $typeOfFundings = $typeOfFundings->map(function ($item) use ($targets) {
            $target = $targets->firstWhere("sub_category_id", $item->ref_target_kpi_category_id);
            $item->target = optional($target)->target ?? 0;
            return $item;
        });

        $proposals = Proposal::with("typeOfFund", "researcher.division", "reportEndProject")
            ->whereHas("reportEndProject", function ($query) use ($year) {
                $mdlReportEndProject = new ReportEndProject();
                $query->whereRaw("YEAR([report_end_project].[created_at]) = ?", [$year])
                    ->where($mdlReportEndProject->qualifyColumn("approval_status"), Approvement::STATUS_APPROVED);
            })
            ->orWhere(function ($query) use ($year) {
                return $query->whereDoesntHave("reportEndProject", function ($query) use ($year) {
                    $mdlReportEndProject = new ReportEndProject();
                    $query->whereRaw("YEAR([report_end_project].[created_at]) = ?", [$year])
                        ->where($mdlReportEndProject->qualifyColumn("approval_status"), Approvement::STATUS_APPROVED);
                })->whereHas("reportQuarterly", function ($query) use ($year) {
                    $mdlReportQuarterly = new ReportQuarterly();
                    $query->where($mdlReportQuarterly->qualifyColumn("year"), $year)
                        ->where($mdlReportQuarterly->qualifyColumn("approval_status"), Approvement::STATUS_APPROVED);
                });
            })->get();

        return [
            "typeOfFundings" => $typeOfFundings,
            "proposals" => $proposals
        ];
    }
}
