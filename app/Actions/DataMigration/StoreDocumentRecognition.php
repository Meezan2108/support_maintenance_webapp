<?php

namespace App\Actions\DataMigration;

use App\Models\Proposal;
use App\Models\Recognition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreDocumentRecognition
{
    public function execute(Recognition $recognition, array $arrFile)
    {
        $recognition->fileable()->create([
            "code_type" => Recognition::FILEABLE_FILE_CODE,
            "access_key" => Str::random(64),
            "file_name" => $arrFile["file_name"],
            "file_type" => $arrFile["file_type"],
            "file_size" => 0,
            "file" => DB::raw($arrFile['file'])
        ]);
    }
}
