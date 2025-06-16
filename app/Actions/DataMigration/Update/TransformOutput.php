<?php

namespace App\Actions\DataMigration\Update;

use App\Models\Approvement;
use App\Models\Originalable;
use App\Models\OutputRnd;
use App\Models\RefOutputStatus;
use App\Models\RefOutputType;
use Carbon\Carbon;

class TransformOutput
{
    public function execute($arrData)
    {
        return $this->extractMainTable($arrData);
    }

    public function extractMainTable($arrData)
    {
        $user = (new FindUserByName)->execute($arrData['project_leader']);

        $outputStatus = RefOutputStatus::query()
            ->where('description', $arrData["status_output"])
            ->first();

        $outputType = RefOutputType::query()
            ->where('description', $arrData['type_of_output'])
            ->first();

        $arrInsert = [
            "user_id" => $user->id,
            "output" => $arrData["output"],
            "type" => $outputType->id,
            "status" => $outputStatus->id,
            "date_output" => $arrData["date"],
            "source_project" => $arrData["source_project"],
            "project_involved" => $arrData["project_involved"]
        ];

        $output = OutputRnd::query()
            ->where([
                'output' => $arrInsert['output'],
                'date_output' => $arrInsert['date_output'],
                'source_project' => $arrInsert['source_project'],
                'project_involved' => $arrData['project_involved']
            ])->first();

        $status = $arrData['status_approval'] == 'Approve'
            ? Approvement::STATUS_APPROVED
            : Approvement::STATUS_REJECTED;

        if ($output) {
            $output->update($arrInsert);
            $output->kpiAchievement()->update([
                "title" => $output->output,
                "category_id" => OutputRnd::CATEGORY_ID,
                "date" => $output->date_output,
                "approval_status" => $status
            ]);
        } else {
            $output = OutputRnd::create($arrInsert);

            $output->kpiAchievement()->create([
                "title" => $output->output,
                "user_id" => $user->id,
                "category_id" => OutputRnd::CATEGORY_ID,
                "date" => $output->date_output,
                "approval_status" => $status
            ]);
        }

        return $output;
    }
}
