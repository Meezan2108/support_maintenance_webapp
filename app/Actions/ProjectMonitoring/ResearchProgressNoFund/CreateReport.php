<?php

namespace App\Actions\ProjectMonitoring\ResearchProgressNoFund;

use App\Models\Fileable;
use App\Models\ReportResearchProgress;
use App\Models\User;
use Illuminate\Support\Str;

class CreateReport
{

    /**
     * Execute the action
     *
     * @param  ReportResearchProgress|null  $report
     * @param  User $user
     * @param  array $arrData
     *
     * @return ReportResearchProgress
     */
    public function execute(?ReportResearchProgress $report, User $user, array $arrData)
    {
        $isSubmited = $arrData["is_submited"] ?? false;

        $arrUpdate = [
            'user_id' => $user->id,
            'ref_division_id' => optional($user->division)->id,

            'ref_report_type_id' => $arrData['ref_report_type_id'] ?? 0,
            'ref_pslkm_id' => $arrData['ref_pslkm_id'] ?? 0,
            'year' => $arrData['year'] ?? '',
            'focus_area' => $arrData['focus_area'] ?? '',
            'issue' => $arrData['issue'] ?? '',
            'strategy' => $arrData['strategy'] ?? '',
            'program' => $arrData['program'] ?? '',

            'project_title' => $arrData['project_title'] ?? '',
            'start_date' => $arrData['start_date'] ?? '',
            'end_date' => $arrData['end_date'] ?? '',

            'date' => $arrData['date'] ?? null,
            'summary' => $arrData['summary'] ?? '',

            'approval_status' => $arrData["approval_status"]
        ];

        if (!$report) {
            $arrUpdate['created_by'] = $user->id;
            $report = ReportResearchProgress::create($arrUpdate);
        } else {
            $arrUpdate['updated_by'] = $user->id;
            $report->update($arrUpdate);
        }


        $arrIdNew = [];
        if ($arrData['new_files']) {
            $requestFiles = $arrData['new_files'];
            foreach ($requestFiles as $file) {
                $fileableFormat = Fileable::prepareForDB($file);

                $fileable = $report->fileable()->create(array_merge([
                    "code_type" => ReportResearchProgress::FILEABLE_DOC_CODE,
                    "access_key" => Str::random(64)
                ], $fileableFormat));

                $arrIdNew[] = $fileable->id;
            }
        }

        $arrIdOld = collect($arrData["old_files"] ?? [])->pluck('id')->toArray();
        $report->fileable()
            ->whereNotIn("id", array_merge($arrIdNew, $arrIdOld))
            ->delete();

        return $report;
    }
}
