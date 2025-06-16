<?php

namespace App\Actions\DataMigration;

use App\Models\Approvement;
use App\Models\Originalable;
use App\Models\OutputRnd;
use App\Models\Proposal;
use App\Models\Publication;
use App\Models\Recognition;
use App\Models\RefDivision;
use App\Models\RefTypeOfFunding;
use App\Models\User;
use Carbon\Carbon;

class TransformProposalOtherDoc2
{

    public function execute($arrData)
    {
        if (!$arrData["idproposal"]) return;

        $originalData = Originalable::query()
            ->where("original_id", $arrData["idproposal"])
            ->where("table_source", "proposalotherdoc2")
            ->first();
        
        if (!$originalData) return;

        $proposalDoc = $this->extractMainTable($originalData, $arrData);

        return $proposalDoc;
    }

    public function extractMainTable($originalData, $arrData)
    {
        $originalProposal = Originalable::where("table_source", "proposal")
            ->where("original_id", $arrData["idproposal"])
            ->first();
        $proposal = Proposal::find($originalProposal->originalable_id);
        // dd($originalProposal->originalable_id);

        $arrOriginal = [
            "table_source" => "proposalotherdoc2",
            "original_id" => $arrData["idproposal"],
            "original_id_alt" => $arrData["idother2"],
            "original_data" => $arrData,
        ];


        $originalDataProposal = Originalable::query()
            ->where("table_source", "proposalotherdoc2")
            ->where("original_id", $arrData["idproposal"])
            ->first();

        $proposal = optional($originalData)->referenceTable
            ?? optional($originalDataProposal)->referenceTable;


        if ($originalData) {
            $originalData->update($arrOriginal);
        } else {
            $proposal->originalable()->create($arrOriginal);
        }

        $proposal->created_at = Carbon::parse($arrData["tarikhupload"])->format("Y-m-d");
        $proposal->updated_at = Carbon::parse($arrData["tarikhupload"])->format("Y-m-d");

        $proposal->save();


        return $proposal;
    }
}
