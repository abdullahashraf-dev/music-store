<?php

namespace Database\Seeders;

use App\Models\Song;
use App\Models\Artist;
use Illuminate\Database\Seeder;

class ArtistSongSeeder extends Seeder
{
    public function run(): void
    {
        $songs = Song::all();
        $artists = Artist::all();

        foreach ($songs as $song) {
            $randomArtists = $artists->random(fake()->numberBetween(1, min(2, $artists->count())));
            $song->artists()->attach($randomArtists);
        }
    }
}
