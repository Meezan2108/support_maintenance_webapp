<?php

namespace App\Actions\KpiMonitoring;

use App\Models\Approvement;
use App\Models\IPR;
use App\Models\OutputRnd;
use App\Models\Proposal;
use App\Models\RefPatent;
use App\Models\User;

class CreateIPRBulkData
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

            $type = RefPatent::where("description", $data["type"])
                ->first();

            $project = isset($data["project_number"])
                ? Proposal::where("project_number", $data["project_number"])
                    ->first()
                : null;

            $kpiItem = IPR::create([
                "user_id" => $user->id,
                "date" => $data["date"],
                "output" => $data["output"],
                "ref_patent_id" => $type->id,
                "proposal_id" => optional($project)->id ?? null,
                "created_by" => $userAuth->id
            ]);

            $kpiItem->kpiAchievement()->create([
                "title" => $kpiItem->output,
                "user_id" => $user->id,
                "category_id" => IPR::CATEGORY_ID,
                "date" => $kpiItem->date,
                "approval_status" => Approvement::STATUS_SUBMITED
            ]);

            (new CreateIPRSubmitNotification)->execute($kpiItem);
            (new CreateIPRSubmitTask)->execute($kpiItem, $userAuth);
        }

    }
}
