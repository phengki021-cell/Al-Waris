<?php

namespace Database\Factories;

use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Source> */
class SourceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => 'SRC-'.fake()->unique()->lexify('????'),
            'title' => fake()->sentence(3),
            'type' => 'kitab',
            'reference' => null,
            'url' => null,
            'excerpt' => null,
            'is_primary' => false,
        ];
    }
}
