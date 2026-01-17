<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Database\Seeder;

class ArtistAlbumSeeder extends Seeder
{
    public function run(): void
    {
        $artists = Artist::all();
        $albums = Album::all();

        foreach ($albums as $album) {
            $randomArtists = $artists->random(fake()->numberBetween(1, min(2, $artists->count())));
            $album->artists()->attach($randomArtists);
        }
    }
}
