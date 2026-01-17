<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

class SongFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => ucwords(fake()->words(fake()->numberBetween(2, 5), true)),
            'duration' => fake()->numberBetween(120, 420),
            'album_id' => Album::factory(),
        ];
    }
}
