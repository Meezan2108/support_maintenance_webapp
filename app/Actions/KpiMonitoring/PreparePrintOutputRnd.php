<?php

namespace App\Actions\KpiMonitoring;

use App\Models\Approvement;
use App\Models\OutputRnd;
use App\Models\RefOutputType;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class PreparePrintOutputRnd
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

        $targetCategory = $targetCategories->firstWhere("code", OutputRnd::CATEGORY_CODE);

        $targetSubCategory = $targetCategory->subCategory;

        $targetPeriod = $targetPeriod->where("type", $targetCategory->type);

        $targetPeriod = $targetPeriod->map(function ($period) use ($year, $researcher, $targetSubCategory, $targets) {
            $month = str_pad($period->options["start_month"], 2, "0", STR_PAD_LEFT);
            $startDate = $year . "-" . $month . "-01";

            $month = str_pad($period->options["end_month"], 2, "0", STR_PAD_LEFT);
            $endDate =  $year . "-" . $month . "-30";

            $outputTypes = RefOutputType::with("targetKpiCategory")
                ->withCount([
                    "output" => function ($query) use ($researcher, $startDate, $endDate) {
                        return $query->whereHas("kpiAchievement", function ($query) use ($researcher, $startDate, $endDate) {
                            return $query->where("approval_status", Approvement::STATUS_APPROVED)
                                ->when($researcher, function ($query) use ($researcher) {
                                    return $query->where("user_id", $researcher->id);
                                })
                                ->where("date", ">=", $startDate)
                                ->where("date", "<=", $endDate);
                        });
                    }
                ])->get();

            $period->outputTypes = $outputTypes;
            $period->targets = $targets->where("period_id", $period->id)
                ->whereIn("sub_category_id", $targetSubCategory->pluck("id"));

            return $period;
        });

        // dd($targetPeriod);

        $outputTypes = RefOutputType::with("targetKpiCategory")
            ->get();

        $outputs = OutputRnd::with("output_type")
            ->whereHas("kpiAchievement", function ($query) use ($year, $researcher) {
                return $query->where("approval_status", Approvement::STATUS_APPROVED)
                    ->whereRaw("YEAR([date]) = ?", $year)
                    ->when($researcher, function ($query) use ($researcher) {
                        return $query->where("user_id", $researcher->id);
                    });
            })
            ->orderBy("date_output", "ASC")
            ->get();

        return [
            "targetPeriod" => $targetPeriod,
            "outputTypes" => $outputTypes,
            "outputs" => $outputs
        ];
    }
}
