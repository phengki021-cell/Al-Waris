<?php

namespace Database\Factories;

use App\Models\Heir;
use App\Models\Share;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Share> */
class ShareFactory extends Factory
{
    public function definition(): array
    {
        $numerator = fake()->randomElement([1, 2, 4, 8]);

        return [
            'heir_id' => Heir::factory(),
            'numerator' => $numerator,
            'denominator' => 8,
            'condition' => 'Kasus DEMO.',
            'condition_hash' => 'demo',
            'note' => 'Data DEMO.',
            'source_id' => Source::factory(),
        ];
    }
}
