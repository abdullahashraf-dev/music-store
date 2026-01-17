<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Song;
use Illuminate\Database\Seeder;

class SongSeeder extends Seeder
{
    public function run(): void
    {
        $albums = Album::all();
        foreach ($albums as $album) {
            $songCount = fake()->numberBetween(3, 5);
            Song::factory($songCount)->create([
                'album_id' => $album->id,
            ]);
        }
    }
}
