<?php

namespace Database\Factories;

use App\Models\Calculation;
use App\Models\CalculationParticipant;
use App\Models\CalculationResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CalculationResult> */
class CalculationResultFactory extends Factory
{
    public function definition(): array
    {
        return [
            'calculation_id' => Calculation::factory(),
            'participant_id' => CalculationParticipant::factory(),
            'numerator' => 1,
            'denominator' => 8,
            'share_text' => '1/8',
            'amount' => fake()->randomFloat(2, 100_000, 10_000_000),
            'rounding_method' => 'none',
            'note' => null,
        ];
    }
}
