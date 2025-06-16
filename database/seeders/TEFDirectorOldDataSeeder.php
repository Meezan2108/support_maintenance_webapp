<?php

namespace Database\Seeders;

use App\Actions\DataMigration\TransformTechnicalEvaluation;
use App\Helpers\EtlHelper;
use Illuminate\Database\Seeder;

class TEFDirectorOldDataSeeder extends Seeder
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

        $file = fopen(storage_path("csv/crims-db-v1/TEFDirector.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];
        $count = 0;

        $isAddHeader = false;

        $arrGrantEvaluator = [];
        $arrRecomended = [];
        while (($row = fgetcsv($file)) !== FALSE) {
            if ($hasHeader && !$isHeaderSkiped) {
                $this->headers = $row;
                $isHeaderSkiped = true;
                continue;
            }

            $arrInsert = $this->transform($row);

            (new TransformTechnicalEvaluation)->execute($arrInsert);

            // print_r($arrInsert);
            // if (!in_array($arrInsert["GrantEvaluator"], $arrGrantEvaluator)) {
            //     $arrGrantEvaluator[$arrInsert["GrantEvaluator"]] = isset($arrGrantEvaluator[$arrInsert["GrantEvaluator"]])
            //         ? $arrGrantEvaluator[$arrInsert["GrantEvaluator"]] + 1
            //         : 1;
            // }

            // if (!in_array($arrInsert["RECOMENDED"], $arrRecomended)) {
            //     $arrRecomended[] = $arrInsert["RECOMENDED"];
            // }
        }

        // echo "Grant Evaluator : ";
        // print_r($arrGrantEvaluator);
        // echo "Recomended : " . implode(", ", $arrRecomended) . "\n";

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
