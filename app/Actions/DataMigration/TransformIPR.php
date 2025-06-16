<?php

namespace App\Actions\DataMigration;

use App\Models\Approvement;
use App\Models\IPR;
use App\Models\Originalable;
use App\Models\OutputRnd;
use App\Models\Proposal;
use App\Models\RefDivision;
use App\Models\RefTypeOfFunding;
use App\Models\User;
use Carbon\Carbon;

class TransformIPR
{
    public function execute($arrData)
    {
        if (!$arrData["title"]) return;

        $ipr = $this->extractMainTable($arrData);

        return $ipr;
    }

    public function extractMainTable($arrData)
    {
        $originalProject = Originalable::where("table_source", "project")
            ->where("original_id", $arrData["idproject"])
            ->first();

        $project = $originalProject->referenceTable;

        $arrInsert = [
            "user_id" => optional($project)->user_id ?? 0,
            "output" => $arrData["title"],
            "ref_patent_id" => $arrData["type"],
            "date" => Carbon::parse($arrData["tahun"] . "-01-01")->format("Y-m-d"),
            "proposal_id" => optional($project)->id,
        ];

        $originalDataIpr = Originalable::query()
            ->where("table_source", "ipr")
            ->where("original_id", $arrData["idintellectualproperty"])
            ->first();

        $ipr = optional($originalDataIpr)->referenceTable;

        if ($ipr) {
            $ipr->update($arrInsert);
            $ipr->kpiAchievement()->update([
                "title" => $ipr->output,
                "category_id" => IPR::CATEGORY_ID,
                "date" => $ipr->date,
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        } else {
            $ipr = IPR::create($arrInsert);

            $ipr->kpiAchievement()->create([
                "title" => $ipr->output,
                "user_id" => optional($project)->user_id ?? 0,
                "category_id" => IPR::CATEGORY_ID,
                "date" => $ipr->date,
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        }

        if ($originalDataIpr) {
            $originalDataIpr->update([
                "original_data" => $arrData
            ]);
        } else {
            $ipr->originalable()->create([
                "table_source" => "ipr",
                "original_id" => $arrData["idintellectualproperty"],
                "original_data" => $arrData
            ]);
        }

        $ipr->created_at = Carbon::parse($arrData["tahun"] . "-01-01")->format("Y-m-d");
        $ipr->updated_at = Carbon::parse($arrData["tahun"] . "-01-01")->format("Y-m-d");

        $ipr->save();


        return $ipr;
    }
}
