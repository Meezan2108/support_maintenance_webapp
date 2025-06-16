<?php

namespace Database\Factories;

use App\Models\Proposal;
use App\Models\ProposalResearcher;
use App\Models\RefDivision;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProposalResearcherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProposalResearcher::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'proposal_id' => 0,
            'name' => $this->faker->sentence(10),
            'nric' => $this->faker->numerify('### ##### ##'),
            'ref_division_id' => 1,
            'ref_position_id' => 1,
            'tel_no' => $this->faker->phoneNumber,
            'fax_no' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
        ];
    }
}
