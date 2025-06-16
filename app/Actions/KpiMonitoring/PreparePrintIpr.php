<?php

namespace App\Actions\KpiMonitoring;

use App\Models\Approvement;
use App\Models\IPR;
use App\Models\OutputRnd;
use App\Models\RefOutputType;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class PreparePrintIpr
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

        $targetCategory = $targetCategories->firstWhere("code", IPR::CATEGORY_CODE);

        $targetPeriod = $targetPeriod->where("type", $targetCategory->type);

        $targetPeriod = $targetPeriod->map(function ($period) use ($year, $researcher, $targetCategory, $targets) {
            $month = str_pad($period->options["start_month"], 2, "0", STR_PAD_LEFT);
            $startDate = $year . "-" . $month . "-01";

            $month = str_pad($period->options["end_month"], 2, "0", STR_PAD_LEFT);
            $endDate =  $year . "-" . $month . "-30";

            $achievement = IPR::query()
                ->whereHas("kpiAchievement", function ($query) use ($startDate, $endDate, $researcher) {
                    return $query->where("approval_status", Approvement::STATUS_APPROVED)
                        ->when($researcher, function ($query) use ($researcher) {
                            return $query->where("user_id", $researcher->id);
                        })
                        ->where("date", ">=", $startDate)
                        ->where("date", "<=", $endDate);
                })->count();

            $period->achievement = $achievement;
            $target = $targets->where("category_id", $targetCategory->id)
                ->firstWhere("period_id", $period->id);
            $period->target = optional($target)->target;

            return $period;
        });

        // dd($targetPeriod);

        $ipr = IPR::query()
            ->whereHas("kpiAchievement", function ($query) use ($year, $researcher) {
                return $query->where("approval_status", Approvement::STATUS_APPROVED)
                    ->whereRaw("YEAR([date]) = ?", $year)
                    ->when($researcher, function ($query) use ($researcher) {
                        return $query->where("user_id", $researcher->id);
                    });
            })
            ->orderBy("date", "ASC")
            ->get();

        return [
            "targetPeriod" => $targetPeriod,
            "ipr" => $ipr
        ];
    }
}
