<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArtistSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::doesntHave('artist')->take(5)->get();
        foreach ($users as $user) {
            Artist::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
