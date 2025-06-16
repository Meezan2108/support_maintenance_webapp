<?php

namespace App\Actions\ApplicationManagement\TechnicalEvaluation;

use App\Helpers\CommentHelper;
use App\Models\Proposal;
use App\Models\User;
use Carbon\Carbon;

class CreateEvaluation
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Proposal $proposal, User $user, array $data, int $step)
    {
        $roleId = CommentHelper::determineRoleByStep($step);

        $evaluation = $proposal->evaluation()
            ->where("evaluator_id", $user->id)
            ->where("role_id", $roleId)
            ->first();

        if ($evaluation) {
            $evaluation->update([
                "date_evaluation" => Carbon::now(),
                "comments" => $data["comments"] ?? '',
                "approval_status" => $data["approval_status"] ?? null
            ]);
        } else {
            $evaluation = $proposal->evaluation()
                ->create([
                    "evaluator_id" => $user->id,
                    "role_id" => $roleId,
                    "date_evaluation" => Carbon::now(),
                    "comments" => $data["comments"] ?? '',
                    "approval_status" => $data["approval_status"] ?? null
                ]);
        }

        foreach ($data["answers"] as $key => $valAnswer) {
            $idQuestion = str_replace("q_", "", $key);

            $answer = $evaluation->answer()
                ->where("ref_answer_category_id", $idQuestion)
                ->first();

            if ($answer) {
                $answer->update([
                    "answer" => $valAnswer
                ]);
            } else {
                $answer = $evaluation->answer()
                    ->create([
                        "ref_answer_category_id" => $idQuestion,
                        "answer" => $valAnswer
                    ]);
            }
        }

        return $evaluation;
    }
}
