<?php

namespace App\Actions\DataMigration\Update;

use App\Models\Approvement;
use App\Models\Recognition;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TransformRecognition
{
    public $dataSource = "recognition";

    public function execute($arrData)
    {
        return $this->extractMainTable($arrData);
    }

    public function extractMainTable($arrData)
    {
        $user = User::where('name', $arrData['project_leader'])
            ->first();

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

        $arrInsert = [
            "user_id" => $user->id,
            "project" => $arrData["recognitions"],
            "recognition" => $arrData["type"],
            "type" => 1,
            "date" => $arrData["date"],
            "proposal_id" => null,
        ];

        $recognition = Recognition::where($arrInsert)->first();

        $status = $arrData['status'] == 'Approve'
            ? Approvement::STATUS_APPROVED
            : Approvement::STATUS_REJECTED;

        if (!$recognition) {
            $recognition  = Recognition::create($arrInsert);
            $recognition->kpiAchievement()->create([
                "title" => $recognition->project . '(' . $recognition->recognition . ')',
                "user_id" => $recognition->user_id,
                "category_id" => Recognition::CATEGORY_ID,
                "date" => $recognition->date,
                "approval_status" => $status
            ]);
        } else {
            $recognition->update($arrInsert);
            $recognition->kpiAchievement()->update([
                "title" => $recognition->project . '(' . $recognition->recognition . ')',
                "user_id" => $recognition->user_id,
                "category_id" => Recognition::CATEGORY_ID,
                "date" => $recognition->date,
                "approval_status" => $status
            ]);
        }

        return $recognition;
    }
}
