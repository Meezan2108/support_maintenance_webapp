<?php

namespace App\Actions\DataMigration;

use App\Models\Proposal;
use App\Models\Recognition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreProposalOtherDoc3
{
    public function execute(Proposal $proposal, array $arrFile)
    {
        $proposal->fileable()->create([
            "code_type" => Proposal::FILEABLE_DOCUMENTATION_CODE,
            "access_key" => Str::random(64),
            "file_name" => $arrFile["file_name"],
            "file_type" => $arrFile["file_type"],
            "file_size" => 0,
            "file" => DB::raw($arrFile['file'])
        ]);
    }
}
