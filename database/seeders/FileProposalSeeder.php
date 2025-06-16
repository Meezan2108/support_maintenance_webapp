<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FileProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info("START SEED " . __CLASS__);

        $dbTarget = config("database.db_target");
        $dbSource = config("database.db_source");

        $targetTable = "proposal";
        $targetModel = "App\Models\Proposal";

        $sourceTable = "Proposal";
        $sourceIdField = "IDProposal";

        $arrSourceFileField = [
            "FileOther1",
            "FileOther2",
            "FileOther3",
            "FileOther4",
            "FileOther5",
            "FileProposal",
        ];

        $arrFileType = ["docx", "pdf", "xlsx"];

        foreach ($arrSourceFileField as $sourceFileField) {
            foreach ($arrFileType as $typeExt) {
                $filename = "{$sourceFileField}.{$typeExt}";
                $sql = "
                    UPDATE {$dbTarget}.dbo.fileable
                    SET {$dbTarget}.dbo.fileable.[file] = CAST(source_tbl.{$sourceFileField} AS VARBINARY(MAX)),
                        {$dbTarget}.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM {$dbTarget}.dbo.fileable f
                    JOIN {$dbTarget}.dbo.{$targetTable} target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = '{$targetModel}'
                    JOIN {$dbTarget}.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = '{$targetModel}'
                    JOIN {$dbSource}.dbo.{$sourceTable} source_tbl
                        ON o.original_id = source_tbl.{$sourceIdField}
                        AND o.originalable_type = '{$targetModel}'
                    WHERE f.file_name = '{$filename}';
                ";

                echo $sql . "\n";
                // DB::statement($sql);
            }
        }

        info("FINISH SEED " . __CLASS__);
    }
}
