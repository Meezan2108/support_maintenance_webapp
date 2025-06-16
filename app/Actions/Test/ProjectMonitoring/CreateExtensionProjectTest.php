<?php

namespace App\Actions\Test\ProjectMonitoring;

use App\Models\ExtensionProject;

class CreateExtensionProjectTest
{
    public function execute($proposal)
    {
        $arrCreate = [
            'user_id' => $proposal->user_id,
            'proposal_id' => $proposal->id,
            'justification' => 'justification',
            'new_fund' => 'new_fund',
            'duration' => 12,
            'date_end_extension' => '2023-09-22',
            'balance_to_date' => 20,
            'approval_status' => ExtensionProject::STATUS_DRAFT,
        ];

        $application = ExtensionProject::create($arrCreate);

        return $application;
    }
}
