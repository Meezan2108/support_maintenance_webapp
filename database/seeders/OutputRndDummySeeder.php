<?php

namespace Database\Seeders;

use App\Models\Approvement;
use App\Models\OutputRnd;
use App\Models\Recognition;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OutputRndDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        OutputRnd::truncate();

        $faker  = Faker::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            $userId = rand(1, 2);


            $outputrnd = OutputRnd::create([
                "user_id" => $userId,
                "date_output" => "2023-" . $faker->date("m-d"),
                "output" => $faker->sentence(),
                "type" => rand(1, 2),
                "status" => rand(1, 5),
                "source_project" => $faker->sentence(2),
                "created_by" => $userId
            ]);

            $outputrnd->kpiAchievement()->create([
                "title" => $outputrnd->output,
                "user_id" => $userId,
                "category_id" => OutputRnd::CATEGORY_ID,
                "date" => $outputrnd->date_output,
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        }
    }
}
