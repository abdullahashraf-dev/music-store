<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateAlbumRequest;
use App\Services\AlbumService;
use App\Http\Resources\AlbumResource;
use App\Core\ApiResponse;
use OpenApi\Attributes as OA;

class AlbumController extends Controller
{
    public function __construct(private AlbumService $albumService) {}

    #[OA\Post(
        path: '/api/albums',
        operationId: 'createAlbum',
        summary: 'Create a new album',
        tags: ['Albums'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    'title' => new OA\Property(property: 'title', type: 'string', example: 'Abbey Road'),
                    'artist_id' => new OA\Property(property: 'artist_id', type: 'integer', example: 1),
                    'artwork_url' => new OA\Property(property: 'artwork_url', type: 'string', example: 'https://example.com/image.jpg'),
                ],
                required: ['title', 'artist_id', 'artwork_url']
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Album created successfully',
                content: new OA\JsonContent(
                    properties: [
                        'success' => new OA\Property(property: 'success', type: 'boolean', example: true),
                        'status_code' => new OA\Property(property: 'status_code', type: 'integer', example: 201),
                        'data' => new OA\Property(
                            property: 'data',
                            properties: [
                                'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                'title' => new OA\Property(property: 'title', type: 'string', example: 'Abbey Road'),
                                'artwork_url' => new OA\Property(property: 'artwork_url', type: 'string', example: 'https://example.com/image.jpg'),
                                'created_at' => new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                'updated_at' => new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(CreateAlbumRequest $request)
    {
        $result = $this->albumService->create($request->validated());

        if ($result->isError()) {
            return ApiResponse::error(
                message: $result->message,
                statusCode: $result->statusCode
            )->toJson();
        }

        return ApiResponse::success(
            data: new AlbumResource($result->data),
            statusCode: $result->statusCode
        )->toJson();
    }
}
