<?php

namespace App\Actions\DataMigration;

use App\Models\Originalable;
use App\Models\Proposal;
use App\Models\ProposalProjectTeam;
use App\Models\RefProjectCostSeries;
use App\Models\User;

class TransformProposalProjectCost
{
    public function execute($arrData)
    {

        $originalDataProject = Originalable::query()
            ->where("original_id", $arrData["PROJECTNUMBER"])
            ->where("table_source", "project")
            ->first();

        $proposal = optional($originalDataProject)->referenceTable;
        if (!$proposal) {
            print_r($arrData["PROJECTNUMBER"]);
            echo "\n";
            return;
        }

        $this->createProjectCost($proposal, $arrData);
    }

    public function createProjectCost(Proposal $proposal, $arrData)
    {
        $refProjectCostSeries = RefProjectCostSeries::all();

        foreach ($refProjectCostSeries as $series) {
            $projectCost = $proposal->projectCost()
                ->where("ref_project_cost_series_id", $series->id)
                ->first();

            if (!$projectCost) {
                $projectCost = $proposal->projectCost()->create([
                    "ref_project_cost_series_id" => $series->id,
                    "description" => $series->jseries_code,
                    "cost" => 0
                ]);
            }

            $detail = $projectCost->detail()
                ->where("year", $arrData["TAHUN"])
                ->first();

            if (!$detail) {
                $detail = $projectCost->detail()->create([
                    "year" => $arrData["TAHUN"],
                    "cost" => $arrData[$series->jseries_code]
                ]);
            }

            $totalCost = $projectCost->detail()->sum("cost");
            $projectCost->cost = $totalCost;
            $projectCost->save();

            $secId = $arrData["TAHUN"] . $series->id;
        }

        $originalable = $proposal->originalable()
            ->where("table_source", "jseriesproject")
            ->where("original_id", $arrData["IDJSERIESPROJECT"])
            ->first();

        if (!$originalable) {
            $detail->originalable()->create([
                "table_source" => "jseriesproject",
                "original_id" => $arrData["IDJSERIESPROJECT"],
                "original_data" => $arrData
            ]);
        } else {
            $originalable->update([
                "original_data" => $arrData
            ]);
        }
    }
}
