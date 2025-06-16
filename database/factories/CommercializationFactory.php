<?php

namespace Database\Factories;

use App\Models\Commercialization;
use App\Models\IPR;
use App\Models\RefOutputType;
use App\Models\RefPatent;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommercializationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Commercialization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "user_id" => $userId = 1,

            "date" => $this->faker->date('Y-m-d'),
            "name" => $this->faker->sentence(5),
            "category" => RefOutputType::first()->id,
            "taker" => $this->faker->company,

            "proposal_id" => 1,
            "created_by" => $userId
        ];
    }
}
