<?php

namespace App\Actions\Test\ProjectMonitoring;

use App\Models\Approvement;
use App\Models\ReportEndProject;

class CreateEopTest
{
    public function execute($proposal)
    {
        $arrCreate = [
            'user_id' => $proposal->user_id,
            'proposal_id' => $proposal->id,

            'approval_status' => Approvement::STATUS_DRAFT,
        ];

        $report = ReportEndProject::create($arrCreate);

        return $report;
    }
}
