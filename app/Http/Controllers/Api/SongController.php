<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateSongRequest;
use App\Services\SongService;
use App\Http\Resources\SongResource;
use App\Core\ApiResponse;
use OpenApi\Attributes as OA;

class SongController extends Controller
{
    public function __construct(private SongService $songService) {}

    #[OA\Get(
        path: '/api/songs',
        operationId: 'getSongs',
        summary: 'Get all songs',
        tags: ['Songs'],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1, example: 1)),
            new OA\Parameter(name: 'limit', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 15, example: 15)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of songs retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        'success' => new OA\Property(property: 'success', type: 'boolean', example: true),
                        'status_code' => new OA\Property(property: 'status_code', type: 'integer', example: 200),
                        'data' => new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                    'title' => new OA\Property(property: 'title', type: 'string', example: 'Hey Jude'),
                                    'duration' => new OA\Property(property: 'duration', type: 'integer', example: 423),
                                    'album' => new OA\Property(
                                        property: 'album',
                                        properties: [
                                            'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                            'title' => new OA\Property(property: 'title', type: 'string', example: 'Abbey Road'),
                                            'artwork_url' => new OA\Property(property: 'artwork_url', type: 'string', example: 'https://example.com/image.jpg'),
                                        ]
                                    ),
                                    'artists' => new OA\Property(
                                        property: 'artists', 
                                        type: 'array',
                                        items: new OA\Items(
                                            properties: [
                                                'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                                'name' => new OA\Property(property: 'name', type: 'string', example: 'The Beatles'),
                                            ]
                                        )
                                    ),
                                    'created_at' => new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                    'updated_at' => new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                ]
                            )
                        ),
                        'pagination' => new OA\Property(
                            property: 'pagination',
                            properties: [
                                'current_page' => new OA\Property(property: 'current_page', type: 'integer', example: 1),
                                'total' => new OA\Property(property: 'total', type: 'integer', example: 50),
                                'per_page' => new OA\Property(property: 'per_page', type: 'integer', example: 15),
                                'last_page' => new OA\Property(property: 'last_page', type: 'integer', example: 4),
                            ]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index()
    {
        $page = (int) request()->query('page', 1);
        $limit = (int) request()->query('limit', 10);

        $result = $this->songService->getAll($page, $limit);

        if ($result->isError()) {
            return ApiResponse::error(
                message: $result->message,
                statusCode: $result->statusCode
            )->toJson();
        }

        $response = ApiResponse::success()->withPagination(
            $result->data,
            SongResource::collection($result->data->items())
        );

        return response()->json($response);
    }

    #[OA\Get(
        path: '/api/songs/{id}',
        operationId: 'showSong',
        summary: 'Get song by ID',
        tags: ['Songs'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Song retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        'success' => new OA\Property(property: 'success', type: 'boolean', example: true),
                        'status_code' => new OA\Property(property: 'status_code', type: 'integer', example: 200),
                        'data' => new OA\Property(
                            property: 'data',
                            properties: [
                                'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                'title' => new OA\Property(property: 'title', type: 'string', example: 'Hey Jude'),
                                'duration' => new OA\Property(property: 'duration', type: 'integer', example: 423),
                                'album' => new OA\Property(
                                    property: 'album',
                                    properties: [
                                        'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                        'title' => new OA\Property(property: 'title', type: 'string', example: 'Abbey Road'),
                                        'artwork_url' => new OA\Property(property: 'artwork_url', type: 'string', example: 'https://example.com/image.jpg'),
                                    ]
                                ),
                                'artists' => new OA\Property(
                                    property: 'artists',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                            'name' => new OA\Property(property: 'name', type: 'string', example: 'The Beatles'),
                                        ]
                                    )
                                ),
                                'created_at' => new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                'updated_at' => new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Song not found'),
        ]
    )]
    public function show($id)
    {
        $result = $this->songService->getById((int) $id);

        if ($result->isError()) {
            return ApiResponse::notFound($result->message)->toJson();
        }

        return ApiResponse::success(
            data: new SongResource($result->data)
        )->toJson();
    }

    #[OA\Post(
        path: '/api/songs',
        operationId: 'createSong',
        summary: 'Create a new song',
        tags: ['Songs'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    'title' => new OA\Property(property: 'title', type: 'string', example: 'Hello'),
                    'artist_id' => new OA\Property(property: 'artist_id', type: 'integer', example: 1),
                    'album_id' => new OA\Property(property: 'album_id', type: 'integer', example: 1),
                    'duration' => new OA\Property(property: 'duration', type: 'integer', example: 240),
                ],
                required: ['title', 'artist_id']
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Song created successfully',
                content: new OA\JsonContent(
                    properties: [
                        'success' => new OA\Property(property: 'success', type: 'boolean', example: true),
                        'status_code' => new OA\Property(property: 'status_code', type: 'integer', example: 201),
                        'data' => new OA\Property(
                            property: 'data',
                            properties: [
                                'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                'title' => new OA\Property(property: 'title', type: 'string', example: 'Hello'),
                                'duration' => new OA\Property(property: 'duration', type: 'integer', example: 240),
                                'album' => new OA\Property(property: 'album', type: 'object'),
                                'artists' => new OA\Property(property: 'artists', type: 'array', items: new OA\Items()),
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
    public function store(CreateSongRequest $request)
    {
        $result = $this->songService->create($request->validated());

        if ($result->isError()) {
            return ApiResponse::error(
                message: $result->message,
                statusCode: $result->statusCode
            )->toJson();
        }

        return ApiResponse::success(
            data: new SongResource($result->data),
            statusCode: $result->statusCode
        )->toJson();
    }
}
