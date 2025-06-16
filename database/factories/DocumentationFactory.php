<?php

namespace Database\Factories;

use App\Models\Documentation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DocumentationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Documentation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'description' => $this->faker->sentence(10),
            'category' => 1,
            'submission_date' => now(),
            'created_by' => 1,
        ];
    }
}
