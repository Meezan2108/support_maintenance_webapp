<?php

namespace App\Actions\DataMigration\Update;

use App\Models\Approvement;
use App\Models\Commercialization;
use App\Models\OutputRnd;
use App\Models\RefOutputStatus;
use App\Models\RefOutputType;

class TransformCommercialization
{
    public function execute($arrData)
    {
        return $this->extractMainTable($arrData);
    }

    public function extractMainTable($arrData)
    {
        $user = (new FindUserByName)->execute($arrData['project_leader']);

        $category = $this->findCategory($arrData["category"]);

        if (!$user) {
            echo $arrData["project_leader"] . "\n";
        }

        if (!$category) {
            echo $arrData["category"] . "\n";
        }

        $arrInsert = [
            "user_id" => $user->id,
            "name" => $arrData["name"],
            "category" => $category->id,
            "taker" => $arrData["taker"],
            "date" => $arrData["date"],
        ];

        $comm = Commercialization::query()
            ->where([
                'name' => $arrInsert['name'],
                'taker' => $arrInsert['taker'],
                'date' => $arrInsert['date']
            ])->first();

        $status = $arrData['status'] == 'Approve'
            ? Approvement::STATUS_APPROVED
            : Approvement::STATUS_REJECTED;

        if ($comm) {
            $comm->update($arrInsert);
            $comm->kpiAchievement()->update([
                "title" => $comm->name,
                "category_id" => Commercialization::CATEGORY_ID,
                "date" => $comm->date,
                "approval_status" => $status
            ]);
        } else {
            $comm = Commercialization::create($arrInsert);

            $comm->kpiAchievement()->create([
                "title" => $comm->name,
                "user_id" => $user->id,
                "category_id" => OutputRnd::CATEGORY_ID,
                "date" => $comm->date,
                "approval_status" => $status
            ]);
        }

        return $comm;
    }

    protected function findCategory($text)
    {
        return RefOutputType::where('description', $text)
            ->first();
    }
}
