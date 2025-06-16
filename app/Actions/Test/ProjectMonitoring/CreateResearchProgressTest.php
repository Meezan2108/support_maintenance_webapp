<?php

namespace App\Actions\Test\ProjectMonitoring;

use App\Models\Approvement;
use App\Models\ExtensionProject;
use App\Models\RefReportType;
use App\Models\ReportResearchProgress;

class CreateResearchProgressTest
{
    public function execute($proposal)
    {
        $arrCreate = [
            'user_id' => $proposal->user_id,
            'proposal_id' => $proposal->id,
            'ref_report_type_id' => RefReportType::first()->id,
            'year' => 2023,
            'focus_area' => 'Focus Area',
            'issue' => 'Issue',
            'strategy' => 'Strategy',
            'program' => 'Program',
            'date' => '2023-08-30',
            'background' => 'Background',
            'result' => 'Result',
            'summary' => 'Summary',

            'approval_status' => Approvement::STATUS_DRAFT,
        ];

        $report = ReportResearchProgress::create($arrCreate);

        return $report;
    }
}
