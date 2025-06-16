<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\RefStatusProposal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefStatusProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefStatusProposal::truncate();
        info("START SEED " . __CLASS__);

        DB::unprepared('SET IDENTITY_INSERT ' . (new RefStatusProposal())->getTable() . ' ON');

        $file = fopen(storage_path("csv/crims-db-v1/refStatusProposal.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];
        while (($row = fgetcsv($file)) !== FALSE) {
            if ($hasHeader && !$isHeaderSkiped) {
                $isHeaderSkiped = true;
                continue;
            }
            $arrInsert[] = $this->transform($row);
        }

        RefStatusProposal::insert($arrInsert);

        fclose($file);

        DB::unprepared('SET IDENTITY_INSERT ' . (new RefStatusProposal())->getTable() . ' OFF');

        info("FINISH SEED " . __CLASS__);
    }


    protected function transform($oldRow)
    {
        $oldRow = collect($oldRow);
        $oldRow = $oldRow->map(function ($item) {
            $item = trim($item);
            return EtlHelper::transformNull($item);
        });

        return [
            "id" => $oldRow[0],
            "code" => $oldRow[0],
            "description" => $oldRow[1],
            "order" => $oldRow[2],
            "created_at" => now(),
            "updated_at" => now()
        ];
    }
}
