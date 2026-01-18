<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Artist;
use Illuminate\Pagination\LengthAwarePaginator;

class ArtistRepository
{
    public function getAll(int $page = 1, int $limit = 10): LengthAwarePaginator
    {
        return Artist::paginate($limit, ['*'], 'page', $page);
    }

    public function findById(int $id): ?Artist
    {
        return Artist::with(['songs', 'albums'])->findOrFail($id);
    }

    public function create(array $data): Artist
    {
        return Artist::create($data);
    }

    public function update(Artist $artist, array $data): Artist
    {
        $artist->update($data);
        return $artist;
    }

    public function delete(Artist $artist): bool
    {
        return $artist->delete();
    }
}
