<?php

namespace App\Actions\KpiMonitoring;

use App\Models\Approvement;
use App\Models\OutputRnd;
use App\Models\Proposal;
use App\Models\Publication;
use App\Models\Recognition;
use App\Models\RefOutputStatus;
use App\Models\RefOutputType;
use App\Models\RefPubType;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CreateOutputRndBulkData
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

            $type = RefOutputType::where("description", $data["type"])
                ->first();

            $status = RefOutputStatus::where("description", $data["status"])
                ->first();

            $project = isset($data["project_number"])
                ? Proposal::where("project_number", $data["project_number"])
                    ->first()
                : null;

            $kpiItem = OutputRnd::create([
                "user_id" => $user->id,
                "date_output" => $data["date"],
                "output" => $data["output"],
                "source_project" => $data["source"],
                "type" => $type->id,
                "status" => $status->id,
                "proposal_id" => optional($project)->id ?? null,
                "created_by" => $userAuth->id
            ]);

            $kpiItem->kpiAchievement()->create([
                "title" => $kpiItem->output,
                "user_id" => $user->id,
                "category_id" => OutputRnd::CATEGORY_ID,
                "date" => $kpiItem->date_output,
                "approval_status" => Approvement::STATUS_SUBMITED
            ]);

            (new CreateOutputRndSubmitNotification)->execute($kpiItem);
            (new CreateOutputRndSubmitTask)->execute($kpiItem, $userAuth);
        }

    }
}
