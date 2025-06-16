<?php

namespace App\Actions\DataMigration\Update;

use App\Models\Approvement;
use App\Models\IPR;
use App\Models\RefPatent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TransformIPR
{
    public function execute($arrData)
    {
        return $this->extractMainTable($arrData);
    }

    public function extractMainTable($arrData)
    {
        $user = (new FindUserByName)->execute($arrData['project_leader']);

        if (!$user) {
            $code = strtoupper(Str::random(7)) . '-recognition';
            $user = User::create([
                "name" => $arrData["project_leader"],
                "code" => $code,
                "staf_id" => $code,
                "email" => $code . "@koko.my",
                "password" => Hash::make("abc12345"),
            ]);
        }

        $patent = $this->findPatent($arrData["type"]);

        $arrInsert = [
            "user_id" => $user->id,
            "output" => $arrData["output"],
            "ref_patent_id" => $patent->id,
            "date" => $arrData["date"],
        ];

        $ipr = IPR::where([
            "output" => $arrData["output"],
            "date" => $arrData["date"],
        ])->first();

        $status = $arrData["status"] == "Approve"
            ? Approvement::STATUS_APPROVED
            : Approvement::STATUS_REJECTED;

        if ($ipr) {
            $ipr->update($arrInsert);
            $ipr->kpiAchievement()->update([
                "title" => $ipr->output,
                "user_id" => $user->id,
                "category_id" => IPR::CATEGORY_ID,
                "date" => $ipr->date,
                "approval_status" => $status
            ]);
        } else {
            $ipr = IPR::create($arrInsert);

            $ipr->kpiAchievement()->create([
                "title" => $ipr->output,
                "user_id" => $user->id,
                "category_id" => IPR::CATEGORY_ID,
                "date" => $ipr->date,
                "approval_status" => $status
            ]);
        }

        return $ipr;
    }

    protected function findPatent($text)
    {
        $patent = RefPatent::query()
            ->where('description', $text)
            ->first();

        if (!$patent) {
            $patent = RefPatent::create([
                "code" => Str::slug($text),
                "description" => $text
            ]);
        }

        return $patent;
    }
}
