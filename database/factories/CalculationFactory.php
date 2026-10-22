<?php

namespace Database\Factories;

use App\Models\Calculation;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Calculation> */
class CalculationFactory extends Factory
{
    public function definition(): array
    {
        $estate = fake()->randomFloat(2, 10_000_000, 500_000_000);

        return [
            'session_token' => null,
            'reference_code' => 'ALW-'.fake()->unique()->numerify('######'),
            'estate_total' => $estate,
            'debts_total' => 0,
            'bequests_total' => 0,
            'funeral_expense' => 0,
            'net_estate' => $estate,
            'method' => 'amal_ummi',
            'status' => 'valid',
            'notes' => 'Data DEMO untuk demonstrasi.',
        ];
    }
}
