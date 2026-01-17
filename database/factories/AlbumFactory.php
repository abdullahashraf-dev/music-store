<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AlbumFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => ucwords(fake()->words(fake()->numberBetween(2, 4), true)),
            'artwork_url' => 'https://picsum.photos/seed/' . fake()->uuid() . '/300/300',
        ];
    }
}
