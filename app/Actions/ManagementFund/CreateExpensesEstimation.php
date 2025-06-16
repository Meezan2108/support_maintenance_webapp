<?php

namespace App\Actions\ManagementFund;

use App\Models\Proposal;
use App\Models\RefProjectCostSeries;

class CreateExpensesEstimation
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Proposal $proposal, array $arrData)
    {
        $costSeries = RefProjectCostSeries::query()
            ->whereIn("vseries_code", array_keys($arrData))
            ->get();

        foreach ($costSeries as $item) {
            $costList = $arrData[$item->vseries_code] ?? [];

            $costList = collect($costList)->map(function ($cost) use ($item) {
                $cost["ref_project_cost_series_id"] = $item->id;
                $cost["cost"] = array_sum($cost["years"]);
                return $cost;
            })->toArray();

            $this->createCost($proposal, $item, $costList, $arrData["years"]);
        }

        return $proposal;
    }

    protected function createCost(Proposal $proposal, RefProjectCostSeries $costSeries, array $costList, array $years)
    {
        $projectCostId = [];
        foreach ($costList as $item) {
            $projectCost = $proposal->projectCost()
                ->where("id", $item["id"] ?? false)
                ->first();

            if ($projectCost) {
                $projectCost->update([
                    "description" => $item["description"],
                    "cost" => $item["cost"]
                ]);
            } else {
                $projectCost = $proposal->projectCost()
                    ->create([
                        "ref_project_cost_series_id" => $item["ref_project_cost_series_id"],
                        "description" => $item["description"],
                        "cost" => $item["cost"]
                    ]);
            }

            foreach ($item["years"] as $key => $costYear) {

                if (!isset($years[$key])) continue;

                $costDetail = $projectCost->detail()
                    ->where("year", $years[$key] ?? false)
                    ->first();

                if ($costDetail) {
                    $costDetail->update([
                        "year" => $years[$key],
                        "cost" => $costYear
                    ]);
                } else {
                    $costDetail = $projectCost->detail()->create([
                        "year" => $years[$key],
                        "cost" => $costYear
                    ]);
                }
            }

            $deleteDetail = $projectCost->detail()
                ->whereNotIn("year", $years)
                ->get();

            foreach ($deleteDetail as $costDetail) {
                $costDetail->delete();
            }

            $projectCostId[] = $projectCost->id;
        }

        $deleteCost = $proposal->projectCost()
            ->where("ref_project_cost_series_id", $costSeries->id)
            ->whereNotIn("id", $projectCostId)
            ->get();

        foreach ($deleteCost as $projectCost) {
            $projectCost->delete();
        }

        return $proposal;
    }
}
