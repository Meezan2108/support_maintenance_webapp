<?php

namespace App\Actions\KpiMonitoring;

use App\Models\Approvement;
use App\Models\Publication;
use App\Models\RefPubType;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class PreparePrintPublications
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
        $targetCategory = $targetCategories->firstWhere("code", Publication::CATEGORY_CODE);

        $targetPeriodSel = $targetPeriod->where("type", $targetCategory->type);

        $targetPeriodSel = $targetPeriodSel->map(function ($period) use ($year, $researcher, $targets, $targetCategory) {
            $month = str_pad($period->options["start_month"], 2, "0", STR_PAD_LEFT);
            $startDate = $year . "-" . $month . "-01";

            $month = str_pad($period->options["end_month"], 2, "0", STR_PAD_LEFT);
            $endDate =  $year . "-" . $month . "-30";

            $pubTypes = RefPubType::withCount([
                "publications" => function ($query) use ($researcher, $startDate, $endDate) {
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

            $period->pubTypes = $pubTypes;
            $target = $targets->where("category_id", $targetCategory->id)
                ->firstWhere("period_id", $period->id);
            $period->target = optional($target)->target;

            return $period;
        });

        $pubTypes = RefPubType::with("publications.kpiAchievement")
            ->whereHas("publications.kpiAchievement", function ($query) use ($year, $researcher) {
                return $query->whereRaw("YEAR([date]) = ?", $year)
                    ->when($researcher, function ($query) use ($researcher) {
                        return $query->where("user_id", $researcher->id);
                    })
                    ->where("approval_status", Approvement::STATUS_APPROVED);
            })->get();

        $publications = Publication::with("type")
            ->whereHas("kpiAchievement", function ($query) use ($year, $researcher) {
                return $query->where("approval_status", Approvement::STATUS_APPROVED)
                    ->whereRaw("YEAR([date]) = ?", $year)
                    ->when($researcher, function ($query) use ($researcher) {
                        return $query->where("user_id", $researcher->id);
                    });
            })
            ->orderBy("date_published", "ASC")
            ->get();

        return [
            "targetPeriod" => $targetPeriodSel,
            "pubTypes" => $pubTypes,
            "publications" => $publications
        ];
    }
}
