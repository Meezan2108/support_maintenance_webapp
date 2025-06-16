<?php

namespace Database\Factories;

use App\Helpers\DateHelper;
use App\Models\ImportedGermplasm;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImportedGermplasmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ImportedGermplasm::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $year = rand(2021, 2023);
        $quarter = rand(1, 4);

        return [
            "user_id" => $userId = 1,

            'date' => DateHelper::calcDateByQuarter($year, $quarter),
            'year' => $year,
            'quarter' => $quarter,
            "no_germplasm" => rand(1, 100),

            "proposal_id" => 1,
            "created_by" => $userId
        ];
    }
}
