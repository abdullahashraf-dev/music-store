<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Song;
use Illuminate\Pagination\LengthAwarePaginator;

class SongRepository
{
    public function getAll(int $page = 1, int $limit = 10): LengthAwarePaginator
    {
        return Song::with(['album', 'artists'])->paginate($limit, ['*'], 'page', $page);
    }

    public function findById(int $id): ?Song
    {
        return Song::with(['album', 'artists'])->findOrFail($id);
    }

    public function create(array $data): Song
    {
        return Song::create($data);
    }

    public function update(Song $song, array $data): Song
    {
        $song->update($data);
        return $song;
    }

    public function delete(Song $song): bool
    {
        return $song->delete();
    }
}
