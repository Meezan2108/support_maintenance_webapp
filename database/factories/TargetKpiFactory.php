<?php

namespace Database\Factories;

use App\Models\Approvement;
use App\Models\IPR;
use App\Models\RefTargetKpiCategory;
use App\Models\RefTargetKpiPeriod;
use App\Models\TargetKpi;
use Illuminate\Database\Eloquent\Factories\Factory;

class TargetKpiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TargetKpi::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $refTargetCategory = RefTargetKpiCategory::first();

        return [
            "user_id" => $userId = 1,

            "year" => rand(2020, date('Y')),
            "period_id" => RefTargetKpiPeriod::where('type', $refTargetCategory->type)->first()->id,
            "category_id" => $refTargetCategory->id,
            "sub_category_id" => null,
            "target" => 25,
            "approval_status" => Approvement::STATUS_DRAFT,

            "created_by" => $userId
        ];
    }
}
