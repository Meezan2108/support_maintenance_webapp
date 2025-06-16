<?php

namespace App\Actions\ProjectMonitoring\Mar;

use App\Models\Fileable;
use App\Models\ReportMilestone;
use App\Models\ReportQuarterly;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UpdateAttachment
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ReportQuarterly $report, array $requestFiles, $arrData)
    {
        $arrIdNew = [];
        foreach ($requestFiles as $file) {
            $fileableFormat = Fileable::prepareForDB($file);

            $fileable = $report->reportMilestone->fileable()->create(array_merge([
                "code_type" => ReportMilestone::FILEABLE_REPORT_MILESTONE_CODE,
                "access_key" => Str::random(64)
            ], $fileableFormat));

            $arrIdNew[] = $fileable->id;
        }

        $arrIdOld = collect($arrData["old_files"] ?? [])->pluck('id')->toArray();
        $report->reportMilestone->fileable()
            ->whereNotIn("id", array_merge($arrIdNew, $arrIdOld))
            ->delete();

        return $report;
    }
}
