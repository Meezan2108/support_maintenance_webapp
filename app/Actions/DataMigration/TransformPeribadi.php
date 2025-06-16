<?php

namespace App\Actions\DataMigration;

use App\Models\Approvement;
use App\Models\Originalable;
use App\Models\RefDivision;
use App\Models\RefPosition;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TransformPeribadi
{
    public function execute($arrData)
    {
        $user = User::query()
            ->where("staf_id", $arrData["NOKT"])
            ->first();

        $division = RefDivision::query()
            ->where("code", $arrData["BAHAGIAN"])
            ->first();

        $position = RefPosition::query()
            ->where("code", $arrData["JAWATAN"])
            ->first();

        if (!$user) {
            $user = User::create([
                "name" => $arrData["NAMA"],
                "code" => $arrData["NOKT"],
                "staf_id" => $arrData["NOKT"],
                "ref_division_id" => optional($division)->id ?? null,
                "ref_position_id" => optional($position)->id ?? null,
                "tel_no" => null,
                "fax_no" => null,
                "email" => $arrData["NOKT"] . "@peribadi.csv",
                "password" => Hash::make("abc12345"),
                "created_at" => $arrData["TLANTIKSKR"],
                "updated_at" => $arrData["TLANTIKSKR"],
            ]);
        }

        $originalable = Originalable::query()
            ->where("original_id", str_replace("K", "", $arrData["NOKT"]))
            ->where("table_source", "peribadi")
            ->first();

        if (!$originalable) {
            $user->originalable()->create([
                "original_id" => str_replace("K", "", $arrData["NOKT"]),
                "original_data" => $arrData,
                "table_source" => "peribadi"
            ]);
        } else {
            $originalable->update([
                "original_data" => $arrData
            ]);
        }

        return $user;
    }
}
