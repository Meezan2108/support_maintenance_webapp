<?php

namespace Database\Factories;

use App\Models\Publication;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Publication::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "user_id" => 1,
            "date_published" => $this->faker->date('Y-m-d'),
            "title" => $this->faker->sentence(10),
            "ref_pub_type_id" => 1,
            "publisher" => $this->faker->company,
            "proposal_id" => 1,
            "created_by" => 1
        ];
    }
}
