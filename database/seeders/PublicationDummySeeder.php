<?php

namespace Database\Seeders;

use App\Models\Approvement;
use App\Models\KpiAchievement;
use App\Models\Publication;
use App\Models\RefPubType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PublicationDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Publication::truncate();
        KpiAchievement::truncate();

        $faker  = Faker::create('id_ID');

        // $researcher = User::whereHas("roles", function ($query) {
        //     return $query->where("id", User::ROLE_RESEARCHER);
        // });

        $pubTypes = RefPubType::all();

        for ($i = 1; $i <= 50; $i++) {
            $type = $pubTypes->random();
            $userId = rand(1, 2);
            $publication = Publication::create([
                "user_id" => $userId,
                "date_published" => "2023-" . $faker->date("m-d"),
                "title" => $faker->sentence(),
                "ref_pub_type_id" => $type->id,
                "publisher" => $faker->sentence(3),
                "created_by" => $userId
            ]);

            $publication->kpiAchievement()->create([
                "title" => $publication->title,
                "user_id" => $userId,
                "category_id" => Publication::CATEGORY_ID,
                "date" => $publication->date_published,
                "approval_status" => Approvement::STATUS_APPROVED
            ]);
        }
    }
}
