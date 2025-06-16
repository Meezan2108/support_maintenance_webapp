<?php

namespace Database\Seeders;

use App\Models\Approvement;
use App\Models\KpiAchievement;
use App\Models\Publication;
use App\Models\Recognition;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RecognitionDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Recognition::truncate();

        $faker  = Faker::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            $userId = rand(1, 2);

            $recognition = Recognition::create([
                "user_id" => $userId,
                "date" => "2023-" . $faker->date("m-d"),
                "recognition" => $faker->sentence(),
                "type" => rand(1, 2),
                "project" => $faker->sentence(),
                "created_by" => $userId
            ]);


            $recognition->kpiAchievement()->create([
                "title" => $recognition->recognition,
                "user_id" => $userId,
                "category_id" => Recognition::CATEGORY_ID,
                "date" => $recognition->date,
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        }
    }
}
