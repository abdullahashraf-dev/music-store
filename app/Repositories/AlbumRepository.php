<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Album;
use Illuminate\Pagination\LengthAwarePaginator;

class AlbumRepository
{
    public function getAll(int $page = 1, int $limit = 10): LengthAwarePaginator
    {
        return Album::paginate($limit, ['*'], 'page', $page);
    }

    public function findById(int $id): ?Album
    {
        return Album::findOrFail($id);
    }

    public function create(array $data): Album
    {
        return Album::create($data);
    }

    public function update(Album $album, array $data): Album
    {
        $album->update($data);
        return $album;
    }

    public function delete(Album $album): bool
    {
        return $album->delete();
    }
}
