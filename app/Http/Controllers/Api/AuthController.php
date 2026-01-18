<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Services\AuthService;
use App\Http\Resources\UserResource;
use App\Core\ApiResponse;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Music Store API',
    version: '1.0.0',
    description: 'API for managing music store with artists, albums, and songs',
)]
#[OA\Server(
    url: 'http://localhost:8000',
    description: 'Development Server'
)]
class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    #[OA\Post(
        path: '/api/auth/register',
        operationId: 'registerUser',
        summary: 'Register a new user',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['username', 'email', 'password', 'password_confirmation'],
                properties: [
                    'username' => new OA\Property(property: 'username', type: 'string', example: 'johndoe'),
                    'email' => new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                    'password' => new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                    'password_confirmation' => new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'password123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'User registered successfully',
                content: new OA\JsonContent(
                    properties: [
                        'success' => new OA\Property(property: 'success', type: 'boolean', example: true),
                        'status_code' => new OA\Property(property: 'status_code', type: 'integer', example: 201),
                        'data' => new OA\Property(
                            property: 'data',
                            properties: [
                                'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                'name' => new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                'email' => new OA\Property(property: 'email', type: 'string', example: 'john@example.com'),
                                'username' => new OA\Property(property: 'username', type: 'string', example: 'johndoe'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        if ($result->isError()) {
            return ApiResponse::error(
                message: $result->message,
                errors: $result->errors,
                statusCode: $result->statusCode
            )->toJson();
        }

        return ApiResponse::success(
            data: new UserResource($result->data),
            statusCode: $result->statusCode
        )->toJson();
    }

    #[OA\Post(
        path: '/api/auth/login',
        operationId: 'loginUser',
        summary: 'Login user',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    'email' => new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                    'password' => new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(
                    properties: [
                        'success' => new OA\Property(property: 'success', type: 'boolean', example: true),
                        'status_code' => new OA\Property(property: 'status_code', type: 'integer', example: 200),
                        'data' => new OA\Property(
                            property: 'data',
                            properties: [
                                'user' => new OA\Property(
                                    property: 'user',
                                    properties: [
                                        'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                        'name' => new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                        'email' => new OA\Property(property: 'email', type: 'string', example: 'john@example.com'),
                                        'username' => new OA\Property(property: 'username', type: 'string', example: 'johndoe'),
                                    ]
                                ),
                                'access_token' => new OA\Property(property: 'access_token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Invalid credentials'),
        ]
    )]
    public function login(LoginRequest $request)
    {
        $result = $this->authService->login(
            $request->email,
            $request->password
        );

        if ($result->isError()) {
            return ApiResponse::error(
                message: $result->message,
                errors: $result->errors,
                statusCode: $result->statusCode
            )->toJson();
        }

        return ApiResponse::success(
            data: [
                'user' => new UserResource($result->data['user']),
                'access_token' => $result->data['access_token'],
                ],
            statusCode: $result->statusCode
        )->toJson();
    }
}
