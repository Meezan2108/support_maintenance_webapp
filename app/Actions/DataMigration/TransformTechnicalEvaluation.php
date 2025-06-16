<?php

namespace App\Actions\DataMigration;

use App\Models\Approvement;
use App\Models\Originalable;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Models\User;
use DateTime;

class TransformTechnicalEvaluation
{
    public function execute($arrData)
    {
        $originalDataProject = Originalable::query()
            ->where("original_id", $arrData["IDPROPOSAL"])
            ->where("table_source", "proposal")
            ->first();

        $proposal = optional($originalDataProject)->referenceTable;
        if (!$proposal) {
            echo $arrData["IDPROPOSAL"];
            echo "\n";
            return;
        }

        $evaluator = User::query()
            ->where("staf_id", $arrData["evaluator"])
            ->first();

        if (!$evaluator) {
            echo "user not fund " . $arrData["evaluator"] . "\n";
            return null;
        }

        $evaluation = $proposal->evaluation()
            ->where("evaluator_id", $evaluator->id)
            ->first();

        if (!$evaluation) {
            $evaluation = $proposal->evaluation()->create([
                "evaluator_id" => $evaluator->id,
                "date_evaluation" => $arrData["DATEevaluation"],
                "approval_status" => Approvement::STATUS_APPROVED,
                "comments" => $arrData["COMMENT"]
            ]);
        }

        $evaluation->created_at = $evaluation->date_evaluation;
        $evaluation->updated_at = $evaluation->date_evaluation;

        $evaluation->save();
        return $evaluation;
    }
}
