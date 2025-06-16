<?php

namespace Database\Factories;

use App\Models\OutputRnd;
use App\Models\RefOutputStatus;
use App\Models\RefOutputType;
use Illuminate\Database\Eloquent\Factories\Factory;

class OutputRndFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OutputRnd::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "user_id" => $userId = 1,
            "date_output" => $this->faker->date('Y-m-d'),
            "output" => $this->faker->sentence(10),
            "type" => RefOutputType::first()->id,
            "status" => RefOutputStatus::first()->id,
            "source_project" => "Source Project",
            "proposal_id" => 1,
            "created_by" => $userId
        ];
    }
}
