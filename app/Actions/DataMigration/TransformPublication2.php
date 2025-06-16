<?php

namespace App\Actions\DataMigration;

use App\Models\Approvement;
use App\Models\Originalable;
use App\Models\OutputRnd;
use App\Models\Proposal;
use App\Models\Publication;
use App\Models\RefDivision;
use App\Models\RefTypeOfFunding;
use App\Models\User;
use Carbon\Carbon;

class TransformPublication2
{
    public function execute($arrData)
    {
        if (!$arrData["title"]) return;

        $originalData = Originalable::query()
            ->where("original_id", $arrData["IDPublication"])
            ->where("table_source", "publication")
            ->first();

        $publication = $this->extractMainTable($originalData, $arrData);
    }

    public function extractMainTable($originalData, $arrData)
    {
        $originalProject = Originalable::where("table_source", "project")
            ->where("original_id", $arrData["IDProject"])
            ->first();
        $project = Proposal::find($originalProject->originalable_id);

        $arrInsert = [
            "user_id" => optional($project)->user_id ?? 0,
            "title" => $arrData["title"],
            "ref_pub_type_id" => 1,
            "publisher" => optional($project)->user_id ?? 0,
            "date_published" => Carbon::parse($arrData["tahun"]."-01-01")->format("Y-m-d"),
            "proposal_id" => $project->id,
        ];

        $arrOriginal = [
            "table_source" => "publication2",
            "original_id" => $arrData["IDPublication"],
            "original_data" => $arrData
        ];


        $originalDataPubliacation = Originalable::query()
            ->where("table_source", "publication2")
            ->where("original_id", $arrData["IDPublication"])
            ->first();

        $publication = optional($originalData)->referenceTable
            ?? optional($originalDataPubliacation)->referenceTable;

        if ($publication) {
            $publication->update($arrInsert);

            $publication->kpiAchievement()->update([
                "title" => $publication->title,
                "category_id" => Publication::CATEGORY_ID,
                "date" => $publication->date_published,
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        } else {
            $publication = Publication::create($arrInsert);

            $publication->kpiAchievement()->create([
                "title" => $publication->title,
                "user_id" => optional($project)->user_id ?? 0,
                "category_id" => Publication::CATEGORY_ID,
                "date" => $publication->date_published,
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        }

        if ($originalData) {
            $originalData->update($arrOriginal);
        } else {
            $publication->originalable()->create($arrOriginal);
        }

        $publication->created_at = Carbon::parse($arrData["tahun"]."-01-01")->format("Y-m-d");
        $publication->updated_at = Carbon::parse($arrData["tahun"]."-01-01")->format("Y-m-d");

        $publication->save();


        return $publication;
    }
}
