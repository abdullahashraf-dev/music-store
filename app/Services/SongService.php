<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Result;
use App\Repositories\SongRepository;

class SongService
{
    public function __construct(private SongRepository $songRepository) {}

    public function getAll(int $page = 1, int $limit = 10): Result
    {
        $songs = $this->songRepository->getAll($page, $limit);
        
        return Result::success(
            data: $songs,
        );
    }

    public function getById(int $id): Result
    {
        $song = $this->songRepository->findById($id);

        return Result::success(
            data: $song,
        );
    }

    public function create(array $data): Result
    {
        $song = $this->songRepository->create($data);

        return Result::success(
            data: $song,
            statusCode: 201
        );
    }
}
