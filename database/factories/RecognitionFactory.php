<?php

namespace Database\Factories;

use App\Models\Commercialization;
use App\Models\Recognition;
use App\Models\RefOutputType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecognitionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recognition::class;

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
            "recognition" => "Recognition Title",
            "type" => 1,
            "project" => 'Project',

            "proposal_id" => 1,
            "created_by" => $userId
        ];
    }
}
