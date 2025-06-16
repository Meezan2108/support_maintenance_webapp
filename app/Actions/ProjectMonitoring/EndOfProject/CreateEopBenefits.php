<?php

namespace App\Actions\ProjectMonitoring\EndOfProject;

use App\Models\RefReportEopBenefitsQuestion;
use App\Models\ReportEndProject;
use App\Models\ReportEopBenefit;
use App\Models\User;

class CreateEopBenefits
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ReportEndProject $report, User $user, array $arrData)
    {
        $dbQuestions = RefReportEopBenefitsQuestion::all();

        foreach ($arrData as $key => $answer) {
            if (strpos($key, 'q_') == -1) {
                continue;
            }

            $questionId = str_replace('q_', '', $key);

            $reportAnswer = ReportEopBenefit::query()
                ->where('report_end_project_id', $report->id)
                ->where('ref_report_eop_benefits_question_id', $questionId)
                ->first();

            if (!$reportAnswer) {
                ReportEopBenefit::create([
                    'report_end_project_id' => $report->id,
                    'ref_report_eop_benefits_question_id' => $questionId,
                    'value' => json_encode($answer)
                ]);
            } else {
                $reportAnswer->update([
                    'value' => json_encode($answer)
                ]);
            }
        }

        return $report;
    }
}
