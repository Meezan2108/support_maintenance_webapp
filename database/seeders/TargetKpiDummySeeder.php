<?php

namespace Database\Seeders;

use App\Models\Approvement;
use App\Models\RefTargetKpiCategory;
use App\Models\RefTargetKpiPeriod;
use App\Models\TargetKpi;
use App\Models\User;
use Illuminate\Database\Seeder;

class TargetKpiDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TargetKpi::truncate();

        $categories = RefTargetKpiCategory::with("subCategory")
            ->whereNull("parent_id")
            ->get();

        $users = User::where("id", "<", 3)->get();

        foreach ($users as $user) {
            foreach ($categories as $category) {
                $periods = RefTargetKpiPeriod::query()
                    ->where("type", $category->type)
                    ->get();

                if ($category->subCategory->count() == 0) {
                    foreach ($periods as $period) {
                        TargetKpi::create([
                            "user_id" => $user->id,
                            "year" => date("Y"),
                            "period_id" => $period->id,
                            "category_id" => $category->id,
                            "target" => rand(0, 30),
                            "approval_status" => Approvement::STATUS_APPROVED,
                            "created_by" => $user->id
                        ]);
                    }

                    continue;
                }

                foreach ($category->subCategory as $subCategory) {
                    foreach ($periods as $period) {
                        TargetKpi::create([
                            "user_id" => $user->id,
                            "year" => date("Y"),
                            "period_id" => $period->id,
                            "category_id" => $category->id,
                            "sub_category_id" => $subCategory->id,
                            "target" => rand(0, 30),
                            "approval_status" => Approvement::STATUS_APPROVED,
                            "created_by" => $user->id
                        ]);
                    }
                }
            }
        }
    }
}
