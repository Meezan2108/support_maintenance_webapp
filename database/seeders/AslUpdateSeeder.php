<?php

namespace Database\Seeders;

use App\Actions\DataMigration\Update\TransformIPR;
use App\Helpers\EtlHelper;
use App\Models\AnalyticalServiceLab;
use App\Models\Approvement;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AslUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-update/Analytical Sevice Lab.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];

        while (($row = fgetcsv($file)) !== false) {

            if ($hasHeader && !$isHeaderSkiped) {
                $isHeaderSkiped = true;
                continue;
            }

            $arrInsert = $this->transform($row);


            $asl = AnalyticalServiceLab::query()
                ->where([
                    'date' => $arrInsert['date'],
                    "no_sample" => $arrInsert["no_sample"], //
                    "no_analysis" => $arrInsert["no_analysis"], //
                    "no_analysis_protocol" => $arrInsert["no_analysis_protocol"], //
                ])->first();

            $status = $arrInsert["status"] == "Approve"
                ? Approvement::STATUS_APPROVED
                : Approvement::STATUS_REJECTED;

            if ($asl) {
                $asl->update([
                    "user_id" => 0,
                    'date' => $arrInsert['date'],
                    "no_sample" => $arrInsert["no_sample"], //
                    "no_analysis" => $arrInsert["no_analysis"], //
                    "no_analysis_protocol" => $arrInsert["no_analysis_protocol"], //
                ]);

                $asl->kpiAchievement()->update([
                    "title" => Carbon::parse($asl->date)->format("Y-m-d"),
                    "user_id" => $asl->user_id,
                    "category_id" => AnalyticalServiceLab::CATEGORY_ID,
                    "date" => $asl->date,
                    "approval_status" => $status
                ]);
            } else {
                $asl = AnalyticalServiceLab::create([
                    "user_id" => 0,
                    'date' => $arrInsert['date'],
                    "no_sample" => $arrInsert["no_sample"], //
                    "no_analysis" => $arrInsert["no_analysis"], //
                    "no_analysis_protocol" => $arrInsert["no_analysis_protocol"], //
                ]);

                $asl->kpiAchievement()->create([
                    "title" => Carbon::parse($asl->date)->format("Y-m-d"),
                    "user_id" => $asl->user_id,
                    "category_id" => AnalyticalServiceLab::CATEGORY_ID,
                    "date" => $asl->date,
                    "approval_status" => $status
                ]);
            }
        }

        fclose($file);

        info("FINISH SEED " . __CLASS__);
    }

    protected function transform($oldRow)
    {
        $oldRow = collect($oldRow);
        $oldRow = $oldRow->map(function ($item) {
            $item = EtlHelper::transformNull($item);
            return $item;
        });

        return [
            "date" => $oldRow[0], //
            "no_sample" => $oldRow[1], //
            "no_analysis" => $oldRow[2], //
            "no_analysis_protocol" => $oldRow[3], //
            "status" => $oldRow[4], //
        ];
    }
}
