<?php

declare(strict_types=1);

namespace App\Core;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public function __construct(
        private bool $success = true,
        private mixed $data = null,
        private ?string $message = null,
        private ?array $errors = null,
        private int $statusCode = 200
    ) {}

    public static function success(mixed $data = null, int $statusCode = 200): self
    {
        return new self(
            success: true,
            data: $data,
            message: null,
            statusCode: $statusCode
        );
    }

    public static function error(string $message, ?array $errors = null, int $statusCode = 400): self
    {
        return new self(
            success: false,
            message: $message,
            errors: $errors,
            statusCode: $statusCode
        );
    }

    public static function validationError(array $errors, int $statusCode = 422): self
    {
        return new self(
            success: false,
            message: 'Validation failed',
            errors: $errors,
            statusCode: $statusCode
        );
    }

    public static function unauthorized(string $message = 'Unauthorized'): self
    {
        return new self(
            success: false,
            message: $message,
            statusCode: 401
        );
    }

    public static function forbidden(string $message = 'Forbidden'): self
    {
        return new self(
            success: false,
            message: $message,
            statusCode: 403
        );
    }

    public static function notFound(string $message = 'Not found'): self
    {
        return new self(
            success: false,
            message: $message,
            statusCode: 404
        );
    }

    public function toArray(): array
    {
        $response = [
            'success' => $this->success,
            'status_code' => $this->statusCode,
        ];

        if ($this->message !== null) {
            $response['message'] = $this->message;
        }

        if ($this->data !== null) {
            $response['data'] = $this->data;
        }

        if ($this->errors !== null) {
            $response['errors'] = $this->errors;
        }

        return $response;
    }

    public function withPagination($paginated, mixed $data): array
    {
        return [
            'success' => $this->success,
            'status_code' => $this->statusCode,
            'data' => $data,
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
        ];
    }

    public function toJson(): JsonResponse
    {
        return response()->json($this->toArray(), $this->statusCode);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
