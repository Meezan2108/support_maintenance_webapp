<?php

namespace App\Actions\KpiMonitoring;

use App\Models\Approvement;
use App\Models\Commercialization;
use App\Models\Proposal;
use App\Models\RefOutputType;
use App\Models\User;

class CreateCommercializationBulkData
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

            $category = RefOutputType::where("description", $data["category"])
                ->first();

            $project = isset($data["project_number"])
                ? Proposal::where("project_number", $data["project_number"])
                    ->first()
                : null;

            $kpiItem = Commercialization::create([
                "user_id" => $user->id,
                "date" => $data["date"],
                "name" => $data["name"],
                "category" => $category->id,
                "taker" => $data["taker"],
                "proposal_id" => optional($project)->id ?? null,
                "created_by" => $userAuth->id
            ]);

            $kpiItem->kpiAchievement()->create([
                "title" => $kpiItem->name,
                "user_id" => $user->id,
                "category_id" => Commercialization::CATEGORY_ID,
                "date" => $kpiItem->date,
                "approval_status" => Approvement::STATUS_SUBMITED
            ]);

            (new CreateCommercializationSubmitNotification)->execute($kpiItem);
            (new CreateCommercializationSubmitTask)->execute($kpiItem, $userAuth);
        }

    }
}
