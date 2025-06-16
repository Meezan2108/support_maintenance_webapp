<?php

namespace Database\Factories;

use App\Models\IPR;
use App\Models\OutputRnd;
use App\Models\RefOutputStatus;
use App\Models\RefOutputType;
use App\Models\RefPatent;
use Illuminate\Database\Eloquent\Factories\Factory;

class IprFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IPR::class;

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
            "output" => $this->faker->sentence(10),
            "ref_patent_id" => RefPatent::first()->id,

            "proposal_id" => 1,
            "created_by" => $userId
        ];
    }
}
