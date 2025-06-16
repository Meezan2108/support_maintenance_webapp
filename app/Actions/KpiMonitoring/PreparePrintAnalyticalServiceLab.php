<?php

namespace App\Actions\KpiMonitoring;

use App\Models\AnalyticalServiceLab;
use App\Models\Approvement;
use App\Models\Commercialization;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PreparePrintAnalyticalServiceLab
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

        $targetSubCategories = $targetCategory->subCategory;

        $targetPeriod = $targetPeriod->where("type", $targetCategory->type);

        $targetPeriod = $targetPeriod->map(function ($period) use ($year, $researcher, $targetSubCategories, $targets) {
            $month = str_pad($period->options["start_month"], 2, "0", STR_PAD_LEFT);
            $startDate = $year . "-" . $month . "-01";

            $month = str_pad($period->options["end_month"], 2, "0", STR_PAD_LEFT);
            $endDate = date("Y-m-t", strtotime($year . "-" . $month . "-01"));

            $achievement = AnalyticalServiceLab::query()
                ->whereHas("kpiAchievement", function ($query) use ($startDate, $endDate, $researcher) {
                    return $query->where("approval_status", Approvement::STATUS_APPROVED)
                        ->when($researcher, function ($query) use ($researcher) {
                            return $query->where("user_id", $researcher->id);
                        })
                        ->where("date", ">=", $startDate)
                        ->where("date", "<=", $endDate);
                })->select(
                    DB::raw("SUM([no_sample]) as no_sample"),
                    DB::raw("SUM([no_analysis]) as no_analysis"),
                    DB::raw("SUM([no_analysis_protocol]) as no_analysis_protocol")
                )->get();

            $period->achievement = $achievement->first();
            $period->targets = $targets->where("period_id", $period->id)
                ->whereIn("sub_category_id", $targetSubCategories->pluck("id"));

            return $period;
        });

        // transform
        $transformIdToKey = collect([
            [
                "id" => 19,
                "key" => "no_sample"
            ],
            [
                "id" => 20,
                "key" => "no_analysis"
            ],
            [
                "id" => 21,
                "key" => "no_analysis_protocol"
            ],
        ]);

        $targetSubCategories = $targetSubCategories->map(function ($item) use ($targetPeriod, $transformIdToKey) {
            $totalTarget = 0;
            $totalAchievement = 0;

            $achievements = [];
            $key = $transformIdToKey->firstWhere("id", $item->id)["key"];

            foreach ($targetPeriod as $index => $period) {
                $achievement = $period->achievement->$key ?? 0;
                $achievements[] = $achievement;
                $totalAchievement += $achievement;

                $selTarget = $period->targets->firstWhere("sub_category_id", $item->id);
                $totalTarget += optional($selTarget)->target ?? 0;
            }

            $item->achievements = $achievements;
            $item->total_target = $totalTarget;
            $item->total_achievement = $totalAchievement;

            return $item;
        });

        return [
            "targetPeriod" => $targetPeriod,
            "targetSubCategories" => $targetSubCategories
        ];
    }
}
