<?php

namespace Database\Factories;

use App\Models\Proposal;
use App\Models\RefTypeOfFunding;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProposalFactory extends Factory
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
            'ref_type_of_funding_id' => Proposal::TRF_ID,
            'project_leader_type' => Proposal::TYPE_LEADER_INTERNAL,
            'proposal_type' => Proposal::TYPE_TRF,
            'project_title' => $this->faker->sentence(10),
            'user_id' => 1,
            'working_address' => $this->faker->address,
            'institution' => $this->faker->company,
            'grade' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'keywords' => $this->faker->randomElements(['cocoa', 'information', 'research']),
        ];
    }
}
