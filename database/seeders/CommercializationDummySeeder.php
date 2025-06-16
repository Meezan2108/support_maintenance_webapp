<?php

namespace Database\Seeders;

use App\Models\Approvement;
use App\Models\Commercialization;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommercializationDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Commercialization::truncate();

        $faker  = Faker::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            $userId = rand(1, 2);

            $commercialization = Commercialization::create([
                "user_id" => $userId,
                "date" => "2023-" . $faker->date("m-d"),
                "name" => $faker->sentence(),
                "category" => rand(1, 2),
                "taker" => $faker->company,
                "created_by" => $userId
            ]);

            $commercialization->kpiAchievement()->create([
                "title" => $commercialization->name,
                "user_id" => $userId,
                "category_id" => Commercialization::CATEGORY_ID,
                "date" => $commercialization->date,
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        }
    }
}
