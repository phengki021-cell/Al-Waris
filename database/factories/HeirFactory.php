<?php

namespace Database\Factories;

use App\Models\Heir;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Heir> */
class HeirFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => 'heir-'.fake()->unique()->lexify('????'),
            'name_id' => fake()->words(2, true),
            'name_ar' => null,
            'slug' => fake()->unique()->slug(2),
            'gender' => 'm',
            'category' => 'fard',
            'detail' => 'Data DEMO untuk pengembangan.',
            'source_id' => Source::factory(),
            'is_active' => true,
        ];
    }
}
