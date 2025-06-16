<?php

namespace App\Actions\KpiMonitoring;

use App\Models\AnalyticalServiceLab;
use App\Models\Approvement;
use App\Models\Commercialization;
use App\Models\ImportedGermplasm;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PreparePrintImportedGermplasm
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

        $targetCategory = $targetCategories->firstWhere("code", AnalyticalServiceLab::CATEGORY_CODE);

        $targetPeriod = $targetPeriod->where("type", $targetCategory->type);

        $targetPeriod = $targetPeriod->map(function ($period) use ($year, $researcher, $targetCategory, $targets) {
            $month = str_pad($period->options["start_month"], 2, "0", STR_PAD_LEFT);
            $startDate = $year . "-" . $month . "-01";

            $month = str_pad($period->options["end_month"], 2, "0", STR_PAD_LEFT);
            $endDate = date("Y-m-t", strtotime($year . "-" . $month . "-01"));

            $achievement = ImportedGermplasm::query()
                ->whereHas("kpiAchievement", function ($query) use ($startDate, $endDate, $researcher) {
                    return $query->where("approval_status", Approvement::STATUS_APPROVED)
                        ->when($researcher, function ($query) use ($researcher) {
                            return $query->where("user_id", $researcher->id);
                        })
                        ->where("date", ">=", $startDate)
                        ->where("date", "<=", $endDate);
                })->select(
                    DB::raw("SUM([no_germplasm]) as no_germplasm")
                )->get();

            $period->achievement = $achievement->first()->no_germplasm;
            $target = $targets->where("category_id", $targetCategory->id)
                ->firstWhere("period_id", $period->id);
            $period->target = optional($target)->target;

            return $period;
        });


        $totalTarget = 0;
        $totalAchievement = 0;

        $achievements = [];

        foreach ($targetPeriod as $index => $period) {
            $achievement = $period->achievement ?? 0;
            $achievements[] = $achievement;
            $totalAchievement += $achievement;

            $totalTarget += $period->target ?? 0;
        }

        $targetCategory->achievements = $achievements;
        $targetCategory->total_target = $totalTarget;
        $targetCategory->total_achievement = $totalAchievement;

        return [
            "targetPeriod" => $targetPeriod,
            "targetCategory" => $targetCategory
        ];
    }
}
