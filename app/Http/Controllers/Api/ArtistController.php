<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateArtistRequest;
use App\Services\ArtistService;
use App\Http\Resources\ArtistDetailResource;
use App\Http\Resources\ArtistResource;
use App\Core\ApiResponse;
use OpenApi\Attributes as OA;

class ArtistController extends Controller
{
    public function __construct(private ArtistService $artistService) {}

    #[OA\Get(
        path: '/api/artists',
        operationId: 'getArtists',
        summary: 'Get all artists with pagination',
        tags: ['Artists'],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1, example: 1)),
            new OA\Parameter(name: 'limit', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 15, example: 15)),
        ],
        responses: [
            new OA\Response(
                response: 200, 
                description: 'List of artists retrieved successfully',
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
                                    'name' => new OA\Property(property: 'name', type: 'string', example: 'The Beatles'),
                                    'bio' => new OA\Property(property: 'bio', type: 'string', example: 'Famous rock band from Liverpool'),
                                    'avatar' => new OA\Property(property: 'avatar', type: 'string', example: 'avatars/beatles.jpg'),
                                    'created_at' => new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                    'updated_at' => new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                ]
                            )
                        ),
                        'pagination' => new OA\Property(
                            property: 'pagination',
                            properties: [
                                'current_page' => new OA\Property(property: 'current_page', type: 'integer', example: 1),
                                'total' => new OA\Property(property: 'total', type: 'integer', example: 25),
                                'per_page' => new OA\Property(property: 'per_page', type: 'integer', example: 15),
                                'last_page' => new OA\Property(property: 'last_page', type: 'integer', example: 2),
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

        $result = $this->artistService->getAll($page, $limit);

        if ($result->isError()) {
            return ApiResponse::error(
                message: $result->message,
                statusCode: $result->statusCode
            )->toJson();
        }

        $response = ApiResponse::success()->withPagination(
            $result->data,
            ArtistResource::collection($result->data->items())
        );

        return response()->json($response);
    }

    #[OA\Get(
        path: '/api/artists/{id}',
        operationId: 'showArtist',
        summary: 'Get artist by ID',
        tags: ['Artists'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200, 
                description: 'Artist retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        'success' => new OA\Property(property: 'success', type: 'boolean', example: true),
                        'status_code' => new OA\Property(property: 'status_code', type: 'integer', example: 200),
                        'data' => new OA\Property(
                            property: 'data',
                            properties: [
                                'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                'name' => new OA\Property(property: 'name', type: 'string', example: 'The Beatles'),
                                'bio' => new OA\Property(property: 'bio', type: 'string', example: 'Famous rock band from Liverpool'),
                                'avatar' => new OA\Property(property: 'avatar', type: 'string', example: 'avatars/beatles.jpg'),
                                'songs' => new OA\Property(
                                    property: 'songs',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                            'title' => new OA\Property(property: 'title', type: 'string', example: 'Hey Jude'),
                                        ]
                                    )
                                ),
                                'albums' => new OA\Property(
                                    property: 'albums',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                            'title' => new OA\Property(property: 'title', type: 'string', example: 'Abbey Road'),
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
            new OA\Response(response: 404, description: 'Artist not found'),
        ]
    )]
    public function show($id)
    {
        $result = $this->artistService->getById((int) $id);

        if ($result->isError()) {
            return ApiResponse::notFound($result->message)->toJson();
        }

        return ApiResponse::success(
            data: new ArtistDetailResource($result->data)
        )->toJson();
    }

    #[OA\Post(
        path: '/api/artists',
        operationId: 'createArtist',
        summary: 'Create a new artist',
        tags: ['Artists'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    'name' => new OA\Property(property: 'name', type: 'string', example: 'The Beatles'),
                    'bio' => new OA\Property(property: 'bio', type: 'string', example: 'Famous rock band'),
                    'avatar' => new OA\Property(property: 'avatar', type: 'string', format: 'binary'),
                ],
                required: ['name']
            )
        ),
        responses: [
            new OA\Response(
                response: 201, 
                description: 'Artist created successfully',
                content: new OA\JsonContent(
                    properties: [
                        'success' => new OA\Property(property: 'success', type: 'boolean', example: true),
                        'status_code' => new OA\Property(property: 'status_code', type: 'integer', example: 201),
                        'data' => new OA\Property(
                            property: 'data',
                            properties: [
                                'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                'name' => new OA\Property(property: 'name', type: 'string', example: 'The Beatles'),
                                'bio' => new OA\Property(property: 'bio', type: 'string', example: 'Famous rock band from Liverpool'),
                                'avatar' => new OA\Property(property: 'avatar', type: 'string', example: 'avatars/beatles.jpg'),
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
    public function store(CreateArtistRequest $request)
    {
        $result = $this->artistService->create($request->validated());

        if ($result->isError()) {
            return ApiResponse::error(
                message: $result->message,
                statusCode: $result->statusCode
            )->toJson();
        }

        return ApiResponse::success(
            data: new ArtistResource($result->data),
            statusCode: $result->statusCode
        )->toJson();
    }
}
