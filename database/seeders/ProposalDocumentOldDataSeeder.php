<?php

namespace Database\Seeders;

use App\Actions\DataMigration\StoreProposalDocument;
use App\Actions\DataMigration\TransformProposalDocument;
use App\Helpers\EtlHelper;
use Illuminate\Database\Seeder;

class ProposalDocumentOldDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-v1/ProposalDocument.csv"), 'r');

        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];
        $count = 0;

        $isAddHeader = false;

        while (($row = fgetcsv($file)) !== FALSE) {
            if ($hasHeader && !$isHeaderSkiped) {
                $isHeaderSkiped = true;
                continue;
            }

            $arrInsert = $this->transform($row);

            $proposalDoc =  (new TransformProposalDocument)->execute($arrInsert);

            if (!$proposalDoc) continue;

            $proposalDoc->fileable()->forceDelete();

            if ($arrInsert["ext"] && $arrInsert["doc"]) {
                $ext = EtlHelper::mimeToExtension($arrInsert["ext"]);
                (new StoreProposalDocument)->execute($proposalDoc, [
                    "file_name" => "ProposalDocument" . $ext,
                    "file_type" => $arrInsert["ext"],
                    "file_size" => 0,
                    "file" => $arrInsert["doc"]
                ]);
            }
        }

        // fclose($fileClean);
        fclose($file);

        info("FINISH SEED " . __CLASS__);
    }

    protected function transform($oldRow)
    {
        $oldRow = collect($oldRow);
        $oldRow = $oldRow->map(function ($item) {
            $item = trim($item);
            $item = EtlHelper::transformNull($item);
            return $item;
        });

        return [
            "iddocproposal" => $oldRow[0], //
            "idproposal" => $oldRow[1], //
            "Keterangan" => $oldRow[2], //
            "tarikhupload" => $oldRow[3], //
            "doc" => $oldRow[4], //
            "ext" => $oldRow[5], //
            "Reviewby" => $oldRow[6], //
        ];
    }
}
