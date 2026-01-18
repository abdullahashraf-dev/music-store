<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Result;
use App\Repositories\ArtistRepository;

class ArtistService
{
    public function __construct(private ArtistRepository $artistRepository) {}

    public function getAll(int $page = 1, int $limit = 10): Result
    {
        $artists = $this->artistRepository->getAll($page, $limit);
        
        return Result::success(
            data: $artists,
        );
    }

    public function getById(int $id): Result
    {
        $artist = $this->artistRepository->findById($id);

        return Result::success(
            data: $artist,
        );
    }

    public function create($data): Result
    {
        if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
            $data['avatar'] = $data['avatar']->store('avatars', 'public');
        }

        $data['user_id'] = auth('api')->id();

        $artist = $this->artistRepository->create($data);
        return Result::success(
            data: $artist,
            statusCode: 201
        );
    }
}
