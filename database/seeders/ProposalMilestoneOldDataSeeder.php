<?php

namespace Database\Seeders;

use App\Actions\DataMigration\TransformProposalMilestone;
use App\Actions\DataMigration\TransformProposalProjectCost;
use App\Actions\DataMigration\TransformProposalProjectTeam;
use App\Helpers\EtlHelper;
use Illuminate\Database\Seeder;

class ProposalMilestoneOldDataSeeder extends Seeder
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

        $file = fopen(storage_path("csv/crims-db-v1/MilestoneDetail.csv"), 'r');

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

            (new TransformProposalMilestone)->execute($arrInsert);
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
