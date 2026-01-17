<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtistFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name() . ' ' ,
            'avatar' => 'https://picsum.photos/seed/' . fake()->uuid() . '/200/200',
            'bio' => fake()->paragraph(3),
        ];
    }
}
