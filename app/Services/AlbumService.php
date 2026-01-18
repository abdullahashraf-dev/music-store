<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Result;
use App\Repositories\AlbumRepository;

class AlbumService
{
    public function __construct(private AlbumRepository $albumRepository) {}

    public function getAll(int $page = 1, int $limit = 10): Result
    {
        $albums = $this->albumRepository->getAll($page, $limit);
        
        return Result::success(
            data: $albums,
        );
    }

    public function getById(int $id): Result
    {
        $album = $this->albumRepository->findById($id);

        return Result::success(
            data: $album,
        );
    }

    public function create(array $data): Result
    {
        $album = $this->albumRepository->create($data);

        return Result::success(
            data: $album,
            statusCode: 201
        );
    }
}
