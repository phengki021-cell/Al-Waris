<?php

namespace Database\Factories;

use App\Models\Calculation;
use App\Models\CalculationParticipant;
use App\Models\Heir;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CalculationParticipant> */
class CalculationParticipantFactory extends Factory
{
    public function definition(): array
    {
        $heir = Heir::inRandomOrder()->first() ?? Heir::factory()->create();

        return [
            'calculation_id' => Calculation::factory(),
            'heir_id' => $heir->id,
            'code' => $heir->code,
            'name_id' => $heir->name_id,
            'count' => 1,
            'attributes' => null,
            'sort_order' => 0,
        ];
    }
}
