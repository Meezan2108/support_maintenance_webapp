<?php

namespace Database\Seeders;

use App\Models\Approvement;
use App\Models\IPR;
use App\Models\OutputRnd;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class IprDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IPR::truncate();

        $faker  = Faker::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            $userId = rand(1, 2);


            $ipr = IPR::create([
                "user_id" => $userId,
                "date" => "2023-" . $faker->date("m-d"),
                "output" => $faker->sentence(),
                "ref_patent_id" => rand(1, 5),
                "created_by" => $userId
            ]);

            $ipr->kpiAchievement()->create([
                "title" => $ipr->output,
                "user_id" => $userId,
                "category_id" => IPR::CATEGORY_ID,
                "date" => $ipr->date,
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        }
    }
}
