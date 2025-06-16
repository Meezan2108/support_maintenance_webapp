<?php

namespace Database\Seeders;

use App\Actions\DataMigration\StoreDocumentRecognition;
use App\Actions\DataMigration\TransformRecognition;
use App\Helpers\EtlHelper;
use Illuminate\Database\Seeder;

class RecognitionOldDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info("START SEED " . __CLASS__);

        $file = fopen(storage_path("csv/crims-db-v1/Recognition.csv"), 'r');

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

            $recognition =  (new TransformRecognition)->execute($arrInsert);

            if (!$recognition) continue;

            $recognition->fileable()->forceDelete();

            if ($arrInsert["ext"] && $arrInsert["filereport"]) {
                $ext = EtlHelper::mimeToExtension($arrInsert["ext"]);
                (new StoreDocumentRecognition)->execute($recognition, [
                    "file_name" => "FileRecognition" . $ext,
                    "file_type" => $arrInsert["ext"],
                    "file_size" => 0,
                    "file" => $arrInsert["filereport"]
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
            "IDRecognition" => $oldRow[0], //
            "IDProject" => $oldRow[1], //
            "NamaRecognition" => $oldRow[2], //
            "Tahun" => $oldRow[3], //
            "Tempat" => $oldRow[4], //
            "ext" => $oldRow[5], //
            "filereport" => $oldRow[6], //
        ];
    }
}
