<?php

namespace App\Actions\DataMigration\Update;

use App\Models\Approvement;
use App\Models\Originalable;
use App\Models\Proposal;
use App\Models\Publication;
use App\Models\RefPubType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TransformPublication
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

        $arrExtract = $this->extractType($arrData["type"]);

        $pubType = RefPubType::query()
            ->where('description', $arrExtract["type"])
            ->first();

        if (!$pubType) {
            $pubType = RefPubType::create([
                "code" => Str::slug($arrExtract["type"]),
                "description" => $arrExtract["type"],
            ]);
        }

        $arrInsert = [
            "user_id" => $user->id,
            "title" => $arrData["title"],
            "ref_pub_type_id" => $pubType->id,
            "publisher" => $arrExtract["publisher"],
            "date_published" => $arrData["date"],
            "doi_number" => $arrData["doi_number"]
        ];

        $publication = Publication::query()
            ->where([
                "title" => $arrData["title"],
                "publisher" => $arrData["publisher"],
                "date_published" => $arrData["date"],
                "doi_number" => $arrData["doi_number"]
            ])->first();

        $status = ($arrData['status'] == "Approve")
            ? Approvement::STATUS_APPROVED
            : Approvement::STATUS_REJECTED;

        if ($publication) {
            $publication->update($arrInsert);

            $publication->kpiAchievement()->update([
                "title" => $publication->title,
                "user_id" => $user->id,
                "category_id" => Publication::CATEGORY_ID,
                "date" => $publication->date_published,
                "approval_status" => $status
            ]);
        } else {
            $publication = Publication::create($arrInsert);

            $publication->kpiAchievement()->create([
                "title" => $publication->title,
                "user_id" => $user->id,
                "category_id" => Publication::CATEGORY_ID,
                "date" => $publication->date_published,
                "approval_status" => $status
            ]);
        }

        return $publication;
    }

    protected function extractType($strType)
    {
        $arrTemp = explode("-", $strType);

        return [
            "publisher" => isset($arrTemp[0]) ? trim($arrTemp[0]) : '',
            "type" => isset($arrTemp[1]) ? trim($arrTemp[1]) : '',
        ];
    }
}
