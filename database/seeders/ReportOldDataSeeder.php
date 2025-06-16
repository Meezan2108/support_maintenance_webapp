<?php

namespace Database\Seeders;

use App\Actions\DataMigration\TransformReport;
use App\Helpers\EtlHelper;
use App\Models\ReportEndProject;
use App\Models\ReportMilestone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReportOldDataSeeder extends Seeder
{
    protected $headers;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-v1/Report.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];
        $count = 0;

        $isAddHeader = false;

        while (($row = fgetcsv($file)) !== FALSE) {
            if ($hasHeader && !$isHeaderSkiped) {
                $this->headers = $row;
                $isHeaderSkiped = true;
                continue;
            }

            $arrInsert = $this->transform($row);

            $report = (new TransformReport)->execute($arrInsert);

            if (!$report) continue;

            $report->fileable()->forceDelete();

            if (isset($arrInsert["ReportFile"])) {
                $ext = EtlHelper::mimeToExtension($arrInsert["Ext"]);

                $report->fileable()->create([
                    "code_type" => $arrInsert["reporttype"] == 1
                        ? ReportMilestone::FILEABLE_REPORT_MILESTONE_CODE
                        : ReportEndProject::FILEABLE_DOC_CODE,
                    "access_key" => Str::random(64),
                    "file_name" => "ReportFile." . $ext,
                    "file_type" => $arrInsert["Ext"],
                    "file_size" => 0,
                    "file" => DB::raw($arrInsert['ReportFile'])
                ]);
            }
        }

        fclose($file);

        info("FINISH SEED " . __CLASS__);
    }

    protected function transform($oldRow)
    {
        $oldRow = collect($oldRow);

        $dataKey = $oldRow->map(function ($item, $index) {
            return [
                "key" => EtlHelper::transformNull($this->headers[$index]),
                "data" => EtlHelper::transformNull($item)
            ];
        });

        $originalData = [];
        foreach ($dataKey as $data) {
            $originalData[$data["key"]] = $data["data"];
        }

        return $originalData;
    }
}
