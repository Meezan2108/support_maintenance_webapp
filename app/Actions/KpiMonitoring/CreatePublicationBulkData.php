<?php

namespace App\Actions\KpiMonitoring;

use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\Publication;
use App\Models\RefPubType;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CreatePublicationBulkData
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

            $user = User::where('email', $data["author"])
                ->first();

            $type = RefPubType::where("description", $data["type"])
                ->first();


            $project = isset($data["project_number"])
                ? Proposal::where("project_number", $data["project_number"])
                    ->first()
                : null;

            $publication = Publication::create([
                "user_id" => $user->id,
                "date_published" => $data["date"],
                "title" => $data["publication"],
                "ref_pub_type_id" => optional($type)->id,
                "publisher" => $data["publisher"] ?? '',
                "proposal_id" => optional($project)->id ?? null,
                "created_by" => $userAuth->id
            ]);

            $kpiAchievement = $publication->kpiAchievement()->create([
                "title" => $publication->title,
                "user_id" => $user->id,
                "category_id" => Publication::CATEGORY_ID,
                "date" => $publication->date_published,
                "approval_status" => Approvement::STATUS_SUBMITED
            ]);

            if (isset($data["co_author"])) {
                $arrCoAuthor = explode(",", $data["co_author"]);
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

                (new CreateResearcherInvolved)->execute($publication, $kpiAchievement, $researcherInvolved);
            }

            (new CreatePublicationSubmitNotification)->execute($publication);
            (new CreatePublicationSubmitTask)->execute($publication, $userAuth);
        }

    }
}
