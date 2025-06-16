<?php

namespace Database\Factories;

use App\Models\Proposal;
use App\Models\RefTypeOfFunding;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProposalMilestoneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Proposal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'proposal_id' => 1,
            'activities' => $this->faker->sentence(7),
            'from' => $this->faker->date('Y-m-d'),
            'to' => $this->faker->date('Y-m-d'),
            'order' => rand(0, 5)
        ];
    }
}
