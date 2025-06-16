<?php

namespace App\Actions\DataMigration;

use App\Models\Approvement;
use App\Models\Originalable;
use App\Models\OutputRnd;
use App\Models\Proposal;
use App\Models\Publication;
use App\Models\Recognition;
use App\Models\RefDivision;
use App\Models\RefTypeOfFunding;
use App\Models\User;
use Carbon\Carbon;

class TransformRecognition
{
    public $dataSource = "recognition";

    public function execute($arrData)
    {
        if (!$arrData["NamaRecognition"]) return;

        $originalData = Originalable::query()
            ->where("original_id", $arrData["IDRecognition"])
            ->where("table_source", "recognition")
            ->first();

        $recognition = $this->extractMainTable($originalData, $arrData);

        return $recognition;
    }

    public function extractMainTable($originalData, $arrData)
    {
        $originalProject = Originalable::where("table_source", "project")
            ->where("original_id", $arrData["IDProject"])
            ->first();
        $project = Proposal::find($originalProject->originalable_id);

        $arrInsert = [
            "user_id" => optional($project)->user_id ?? 0,
            "recognition" => $arrData["NamaRecognition"],
            "type" => 1,
            "date" => Carbon::parse($arrData["Tahun"]."-01-01")->format("Y-m-d"),
            "proposal_id" => $project->id,
        ];

        $arrOriginal = [
            "table_source" => "recognition",
            "original_id" => $arrData["IDRecognition"],
            "original_data" => $arrData
        ];


        $originalDataRecognition = Originalable::query()
            ->where("table_source", "recognition")
            ->where("original_id", $arrData["IDRecognition"])
            ->first();

        $recognition = optional($originalData)->referenceTable
            ?? optional($originalDataRecognition)->referenceTable;

        if ($recognition) {
            $recognition->update($arrInsert);

            $recognition->kpiAchievement()->update([
                "title" => $recognition->recognition,
                "category_id" => Recognition::CATEGORY_ID,
                "date" => $recognition->date,
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        } else {
            $recognition = Recognition::create($arrInsert);

            $recognition->kpiAchievement()->create([
                "title" => $recognition->recognition,
                "user_id" => optional($project)->user_id ?? 0,
                "category_id" => Recognition::CATEGORY_ID,
                "date" => $recognition->date,
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        }

        if ($originalData) {
            $originalData->update($arrOriginal);
        } else {
            $recognition->originalable()->create($arrOriginal);
        }

        $recognition->created_at = Carbon::parse($arrData["Tahun"]."-01-01")->format("Y-m-d");
        $recognition->updated_at = Carbon::parse($arrData["Tahun"]."-01-01")->format("Y-m-d");

        $recognition->save();


        return $recognition;
    }
}
