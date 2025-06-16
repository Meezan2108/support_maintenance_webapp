<?php

namespace App\Actions\KpiMonitoring;

use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\Publication;
use App\Models\Recognition;
use App\Models\RefPubType;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CreateRecognitionBulkData
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return void
     */
    public function execute($arrData = [], $userAuth = null)
    {
        foreach ($arrData as $data) {

            $user = User::where('email', $data["project_leader"])
                ->first();

            $type = array_search($data["type"], Recognition::ARR_TYPE);

            $project = isset($data["project_number"])
                ? Proposal::where("project_number", $data["project_number"])
                    ->first()
                : null;

            $kpiItem = Recognition::create([
                "user_id" => $user->id,
                "date" => $data["date"],
                "recognition" => $data["recognition"],
                "type" => $type,
                "project" => $data["event"],
                "proposal_id" => optional($project)->id ?? null,
                "created_by" => $userAuth->id
            ]);

            $kpiAchievement = $kpiItem->kpiAchievement()->create([
                "title" => $kpiItem->recognition,
                "user_id" => $user->id,
                "category_id" => Recognition::CATEGORY_ID,
                "date" => $kpiItem->date,
                "approval_status" => Approvement::STATUS_SUBMITED
            ]);

            if (isset($data["team_member"])) {
                $arrCoAuthor = explode(",", $data["team_member"]);
                $arrCoAuthor = collect($arrCoAuthor)->map(function ($item) {
                    return trim($item);
                })->toArray();

                $researcherInvolved = User::whereIn("email", $arrCoAuthor)
                    ->get()
                    ->map(function ($data) {
                        return [
                            "user_id" => $data->id,
                            "name" => $data->name
                        ];
                    })->toArray();

                (new CreateResearcherInvolved)->execute($kpiItem, $kpiAchievement, $researcherInvolved);
            }

            (new CreateRecognitionSubmitNotification)->execute($kpiItem);
            (new CreateRecognitionSubmitTask)->execute($kpiItem, $userAuth);
        }

    }
}
