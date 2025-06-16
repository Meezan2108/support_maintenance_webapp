<?php

namespace App\Actions\DataMigration;

use App\Models\Approvement;
use App\Models\Originalable;
use App\Models\OutputRnd;
use App\Models\Proposal;
use App\Models\RefDivision;
use App\Models\RefTypeOfFunding;
use App\Models\User;
use Carbon\Carbon;

class TransformOutput
{
    public function execute($arrData)
    {
        if (!$arrData["NamaOutput"]) return;

        $originalData = Originalable::query()
            ->where("original_id", $arrData["OutputID"])
            ->where("table_source", "output")
            ->first();

        $output = $this->extractMainTable($originalData, $arrData);
    }

    public function extractMainTable($originalData, $arrData)
    {
        $originalProject = Originalable::where("table_source", "project")
            ->where("original_id", $arrData["idproject"])
            ->first();
        $project = Proposal::find($originalProject->originalable_id);

        $arrInsert = [
            "user_id" => optional($project)->user_id ?? 0,
            "output" => $arrData["NamaOutput"],
            "type" => 1,
            "status" => 1,
            "date_output" => Carbon::parse($arrData["TahunOutput"]."-01-01")->format("Y-m-d"),
            "source_project" => "-",
            "proposal_id" => $project->id,
        ];

        $arrOriginal = [
            "table_source" => "output",
            "original_id" => $arrData["OutputID"],
            "original_data" => $arrData
        ];


        $originalDataOutput = Originalable::query()
            ->where("table_source", "output")
            ->where("original_id", $arrData["OutputID"])
            ->first();

        $output = optional($originalData)->referenceTable
            ?? optional($originalDataOutput)->referenceTable;

        if ($output) {
            $output->update($arrInsert);
            $output->kpiAchievement()->update([
                "title" => $output->output,
                "category_id" => OutputRnd::CATEGORY_ID,
                "date" => $output->date_output,
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        } else {
            $output = OutputRnd::create($arrInsert);

            $output->kpiAchievement()->create([
                "title" => $output->output,
                "user_id" => optional($project)->user_id ?? 0,
                "category_id" => OutputRnd::CATEGORY_ID,
                "date" => $output->date_output,
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        }

        if ($originalData) {
            $originalData->update($arrOriginal);
        } else {
            $output->originalable()->create($arrOriginal);
        }

        $output->created_at = Carbon::parse($arrData["TahunOutput"]."-01-01")->format("Y-m-d");
        $output->updated_at = Carbon::parse($arrData["TahunOutput"]."-01-01")->format("Y-m-d");

        $output->save();


        return $output;
    }
}
