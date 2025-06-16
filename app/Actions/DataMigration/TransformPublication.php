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

class TransformPublication
{
    public function execute($arrData)
    {
        if (!$arrData["title"]) return;

        $originalData = Originalable::query()
            ->where("original_id", $arrData["idpublication"])
            ->where("table_source", "publication")
            ->first();

        $publication = $this->extractMainTable($originalData, $arrData);
    }

    public function extractMainTable($originalData, $arrData)
    {
        $originalProject = Originalable::where("table_source", "project")
            ->where("original_id", $arrData["idproject"])
            ->first();
        $project = Proposal::find($originalProject->originalable_id);

        $arrInsert = [
            "user_id" => optional($project)->user_id ?? 0,
            "title" => $arrData["title"],
            "ref_pub_type" => 1,
            "publisher" => $arrData["author"],
            "date_published" => $arrData["Pubdate"],
            "proposal_id" => $project->id,
        ];

        $arrOriginal = [
            "table_source" => "publication",
            "original_id" => $arrData["idpublication"],
            "original_data" => $arrData
        ];


        $originalDataPubliacation = Originalable::query()
            ->where("table_source", "publication")
            ->where("original_id", $arrData["idpublication"])
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

        $publication->created_at = $arrData["Pubdate"];
        $publication->updated_at = $arrData["Pubdate"];

        $publication->save();


        return $publication;
    }
}
