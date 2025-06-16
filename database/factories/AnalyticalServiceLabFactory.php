<?php

namespace Database\Factories;

use App\Helpers\DateHelper;
use App\Models\AnalyticalServiceLab;
use App\Models\Documentation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AnalyticalServiceLabFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AnalyticalServiceLab::class;

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
            'user_id' => 1,
            'no_sample' => $this->faker->numberBetween(0, 50),
            'no_analysis' => $this->faker->numberBetween(0, 50),
            'no_analysis_protocol' => $this->faker->numberBetween(0, 50),
            'date' => DateHelper::calcDateByQuarter($year, $quarter),
            'year' => $year,
            'quarter' => $quarter,
            'created_by' => 1,
        ];
    }
}
